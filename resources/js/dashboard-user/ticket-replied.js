import axios from "axios";
import Swal from "sweetalert2";

import "../bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    console.log("💬 Ticket Reply Chat Loaded");

    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");
    const ticketId = document.getElementById("ticket_id")?.value;
    const chatBox = document.getElementById("chat-box");
    const form = document.getElementById("chat-form");
    const messageInput = document.getElementById("message");
    const endChatBtn = document.getElementById("end-chat-btn");
    const chatStatusLabel = document.getElementById("chat-status-label");
    const ticketHeader = document.getElementById("ticket-header");
    const rawStatus = ticketHeader?.dataset.status;
    const uploadInput = document.getElementById("attachment-upload");
    let ticketStatus = "";

    try {
        ticketStatus = JSON.parse(rawStatus);
    } catch (e) {
        ticketStatus = rawStatus;
    }

    const statusSlug =
        typeof ticketStatus === "object" && ticketStatus !== null
            ? ticketStatus.slug
            : ticketStatus;

    if (!ticketId) {
        console.error("❌ Ticket ID not found");
        return;
    }

    const addMessage = (
        message,
        isOwn = false,
        time = null,
        isRead = false,
        attachment = null
    ) => {
        const wrapper = document.createElement("div");
        wrapper.classList.add(
            "message",
            "flex",
            "my-2",
            isOwn ? "justify-end" : "justify-start",
            isOwn ? "own" : "other"
        );

        const msgDiv = document.createElement("div");
        msgDiv.classList.add(
            "p-2",
            "rounded-2xl",
            "inline-block",
            "break-words",
            "shadow",
            "relative",
            isOwn ? "bg-blue-500" : "bg-gray-200",
            isOwn ? "text-white" : "text-black"
        );

        msgDiv.style.maxWidth = "75%";
        msgDiv.style.width = "fit-content";

        if (message && !attachment) {
            const textEl = document.createElement("p");
            textEl.textContent = message;
            textEl.classList.add("whitespace-pre-wrap");
            msgDiv.appendChild(textEl);
        }

        // ---- ATTACHMENT HANDLING ----
        if (attachment) {
            const fileName =
                attachment.name ||
                attachment.file_name ||
                attachment.file_path?.split("/").pop() ||
                "file";

            const ext = fileName.split(".").pop().toLowerCase();

            const fileUrl =
                attachment.url ||
                (attachment.file_path
                    ? `/storage/${attachment.file_path}`
                    : URL.createObjectURL(attachment));

            console.log("FILE URL:", fileUrl);

            // -------- IMAGE FILE --------
            if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
                const link = document.createElement("a");
                link.href = fileUrl;
                link.target = "_blank";
                link.rel = "noopener noreferrer";

                const img = document.createElement("img");
                img.src = fileUrl;
                img.classList.add("w-40", "rounded-xl", "cursor-pointer");

                link.appendChild(img);
                msgDiv.appendChild(link);
            }

            // -------- PDF FILE --------
            else if (ext === "pdf") {
                const link = document.createElement("a");
                link.href = fileUrl;
                link.target = "_blank";
                link.rel = "noopener noreferrer";

                link.innerHTML = `
            <div class="flex items-center gap-2 cursor-pointer underline">
                📄 <span>${fileName}</span>
            </div>
        `;
                msgDiv.appendChild(link);
            }

            // -------- OTHER FILES --------
            else {
                const link = document.createElement("a");
                link.href = fileUrl;
                link.target = "_blank";
                link.rel = "noopener noreferrer";
                link.download = fileName;

                link.innerHTML = `
            <div class="flex items-center gap-2 cursor-pointer underline">
                📎 <span>${fileName}</span>
            </div>
        `;
                msgDiv.appendChild(link);
            }
        }

        // TIME + CHECK ICON
        if (time) {
            const metaEl = document.createElement("div");
            metaEl.classList.add(
                "text-xs",
                "mt-1",
                "flex",
                "items-center",
                "gap-1",
                "justify-end"
            );

            const t = new Date(time);
            const formattedTime = `${t
                .getHours()
                .toString()
                .padStart(2, "0")}:${t
                .getMinutes()
                .toString()
                .padStart(2, "0")}`;

            metaEl.innerHTML += `<span>${formattedTime}</span>`;

            if (isOwn) {
                metaEl.innerHTML += `<span class="check-icon" style="color:${
                    isRead ? "#00FFFF" : "#9ca3af"
                }">${isRead ? "✓✓" : "✓"}</span>`;
            }

            msgDiv.appendChild(metaEl);
        }

        wrapper.appendChild(msgDiv);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    };

    const loadMessages = async () => {
        try {
            const response = await axios.get(
                `${baseUrl}/ticket-replies/${ticketId}`,
                {
                    headers: { Authorization: `Bearer ${token}` },
                }
            );

            chatBox.innerHTML = "";

            let replies = response.data.data.replies || [];
            let attachments = response.data.data.attachments || [];

            // --- NORMALISASI DATA ---
            replies = replies.map((r) => ({
                type: "text",
                message: r.message,
                isOwn: r.is_own,
                time: r.created_at,
                isRead: r.is_read,
                attachment: null,
            }));

            attachments = attachments.map((a) => ({
                type: "file",
                message: null,
                isOwn: a.is_own ?? true,
                time: a.created_at,
                isRead: false,
                attachment: a,
            }));

            // --- GABUNGKAN SEMUA KE SATU ARRAY ---
            let all = [...replies, ...attachments];

            // --- SORT BERDASARKAN created_at (ASC) ---
            all.sort((a, b) => new Date(a.time) - new Date(b.time));

            // --- TAMPILKAN SEMUA PESAN SECARA URUT ---
            all.forEach((item) => {
                addMessage(
                    item.message,
                    item.isOwn,
                    item.time,
                    item.isRead,
                    item.attachment
                );
            });
        } catch (error) {
            console.error("❌ Gagal memuat pesan:", error);
            chatBox.innerHTML =
                '<div class="text-center text-gray-500 mt-5">Gagal memuat pesan.</div>';
        }
    };

    const sendMessage = async (message) => {
        try {
            if (statusSlug?.toLowerCase() === "closed") {
                Swal.fire({
                    icon: "warning",
                    title: "Ticket Ditutup",
                    text: "Anda tidak dapat mengirim pesan baru pada ticket yang sudah ditutup.",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3085d6",
                });
                return;
            }

            const response = await axios.post(
                `${baseUrl}/ticket-replies`,
                { ticket_id: ticketId, message },
                { headers: { Authorization: `Bearer ${token}` } }
            );

            const reply = response.data.data;
            const time = reply.created_at
                ? new Date(reply.created_at)
                : new Date();

            addMessage(message, true, time, null, false);
            messageInput.value = "";
        } catch (error) {
            console.error("❌ Gagal mengirim pesan:", error);
        }
    };

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) sendMessage(message);
    });

    const updateChatUI = (status) => {
        if (!endChatBtn || !chatStatusLabel) return;
        if ((status || "").toLowerCase() === "closed") {
            endChatBtn.style.display = "none";
            chatStatusLabel.style.display = "inline-block";
        } else {
            endChatBtn.style.display = "inline-flex";
            chatStatusLabel.style.display = "none";
        }
    };

    endChatBtn?.addEventListener("click", async () => {
        const result = await Swal.fire({
            title: "Yakin?",
            text: "Yakin ingin Akhiri Tiket ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, akhiri",
            cancelButtonText: "Batal",
        });

        if (!result.isConfirmed) return;

        try {
            const response = await axios.put(
                `${baseUrl}/tickets/${ticketId}/close`,
                {},
                {
                    headers: { Authorization: `Bearer ${token}` },
                }
            );

            if (response.data.success) {
                updateChatUI("closed");
                Swal.fire({
                    icon: "success",
                    title: "Tiket berhasil diakhiri",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.reload();
                });
            }
        } catch (error) {
            console.error("❌ Gagal mengakhiri Tiket:", error);
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Terjadi kesalahan saat mengakhiri Tiket.",
            });
        }
    });

    const uploadAttachment = async (files) => {
        if (!files.length) return;

        // Cek jika tiket sudah ditutup
        if (statusSlug === "closed") {
            Swal.fire({
                icon: "warning",
                title: "Tiket Ditutup",
                text: "Anda tidak dapat mengunggah lampiran karena tiket ini sudah ditutup.",
                confirmButtonText: "OK",
            });
            return;
        }

        const MAX_SIZE = 500 * 1024; // 500 KB
        const existing = window.existingAttachments ?? 0;
        const selected = files.length;

        // Cek ukuran per file
        for (let file of files) {
            if (file.size > MAX_SIZE) {
                Swal.fire({
                    icon: "error",
                    title: "Ukuran Terlalu Besar",
                    text: `File "${file.name}" melebihi batas 500 KB.`,
                });

                uploadInput.value = "";
                return;
            }
        }

        // Validasi max 5 file total
        if (existing + selected > 5) {
            Swal.fire({
                icon: "error",
                title: "Maksimal Lampiran",
                text: `Anda hanya dapat mengunggah maksimal 5 lampiran. 
Saat ini ada ${existing}, dan Anda memilih ${selected} file.`,
            });

            // Reset input agar user bisa upload ulang
            uploadInput.value = "";
            return;
        }

        const formData = new FormData();
        for (let file of files) {
            formData.append("attachments[]", file);
        }

        try {
            await axios.post(
                `/dashboard/user/ticket-reply/${ticketId}/attachments`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            for (let file of files) {
                addMessage(null, true, new Date(), false, file);
            }

            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Lampiran berhasil diunggah.",
                timer: 1200,
                showConfirmButton: false,
            });
        } catch (error) {
            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Gagal Mengunggah",
                text:
                    error.response?.data?.message ??
                    "Terjadi kesalahan saat mengunggah lampiran. Silakan coba lagi.",
            });
        }
    };

    uploadInput.addEventListener("change", async (e) => {
        await uploadAttachment(e.target.files);
    });

    window.Echo.channel(`ticket.${ticketId}`)
        .listen(".TicketReplied", async (e) => {
            console.log("💬 New reply:", e);

            // tampilkan pesan dari orang lain
            if (e.user_id !== token) {
                addMessage(e.message, false, e.created_at, e.sender_name);

                // 🔥 Tambahkan trigger "read" di sini
                try {
                    await axios.get(`${baseUrl}/ticket-replies/${ticketId}`, {
                        headers: { Authorization: `Bearer ${token}` },
                    });
                } catch (err) {
                    console.error("❌ Gagal menandai pesan dibaca:", err);
                }
            }
        })
        .listen(".TicketStatusUpdated", (e) => {
            console.log("💬 Ticket status updated:", e.status);
            const assignedLabel = document.querySelector(".assign");
            const isClosed = e.status === "closed";

            if (endChatBtn)
                endChatBtn.style.display = isClosed ? "none" : "inline-block";
            if (chatStatusLabel)
                chatStatusLabel.style.display = isClosed
                    ? "inline-block"
                    : "none";
            if (assignedLabel)
                assignedLabel.textContent = e.assigned_to || "Menunggu";
            if (isClosed) {
                Swal.fire({
                    icon: "info",
                    title: "Tiket telah ditutup",
                    text: "Tiket sudah diakhiri oleh lawan bicara.",
                    timer: 2500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.reload();
                });
            }
        })
        .listen(".TicketRead", (e) => {
            console.log("👁 Pesan dibaca oleh admin:", e.userId);
            const allOwnMessages = chatBox.querySelectorAll(".own");
            allOwnMessages.forEach((msg) => {
                const check = msg.querySelector(".check-icon");
                if (check) {
                    check.innerHTML = "✓✓";
                    check.style.color = "#00FFFF";
                }
            });
        });

    updateChatUI(statusSlug);
    loadMessages();
});
