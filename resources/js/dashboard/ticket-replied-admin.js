import axios from "axios";
import "../bootstrap";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", () => {
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
    let ticketStatus;

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
        sender = null,
        isRead = false,
        fileUrl = null,
        fileType = null
    ) => {
        const wrapper = document.createElement("div");
        wrapper.classList.add(
            "flex",
            "my-2",
            isOwn ? "justify-end" : "justify-start"
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

        if (isOwn) msgDiv.classList.add("message-own");

        msgDiv.style.maxWidth = "75%";
        msgDiv.style.width = "fit-content";

        // ⬅ tampilkan label pengirim (untuk pesan dari user ke admin)
        if (sender && !isOwn) {
            const senderEl = document.createElement("div");
            senderEl.classList.add("text-xs", "text-gray-500", "mb-1");
            senderEl.textContent = sender;
            msgDiv.appendChild(senderEl);
        }

        // ============================
        //  🔥  TAMPILKAN FILE / FOTO
        // ============================
        if (fileUrl) {
            const ext = fileUrl.split(".").pop().toLowerCase();

            const isImage = ["jpg", "jpeg", "png", "gif", "webp"].includes(ext);

            if (isImage) {
                // 🖼 TAMPILKAN GAMBAR LANGSUNG
                const img = document.createElement("img");
                img.src = fileUrl;
                img.classList.add("rounded-lg", "cursor-pointer");
                img.style.maxWidth = "220px";
                img.style.maxHeight = "180px";
                img.style.objectFit = "cover";

                // klik → buka tab baru
                img.addEventListener("click", () =>
                    window.open(fileUrl, "_blank")
                );

                msgDiv.appendChild(img);
            } else {
                // 📄 FILE BIASA (PDF, DOCX, XLSX, CSV)
                const icon = document.createElement("div");
                icon.classList.add(
                    "text-sm",
                    isOwn ? "text-blue-100" : "text-gray-600"
                );
                icon.innerHTML = `
            <a href="${fileUrl}" target="_blank" class="underline">
                📄 Download File (${ext.toUpperCase()})
            </a>
        `;
                msgDiv.appendChild(icon);
            }
        }

        // ============================
        //  🔥  TAMPILKAN TEKS JIKA ADA
        // ============================
        if (message) {
            const text = document.createElement("div");
            text.textContent = message;
            msgDiv.appendChild(text);
        }

        // ============================
        //  🔥  TIME + READ INDICATOR
        // ============================
        if (time) {
            const t = new Date(time);

            const meta = document.createElement("div");
            meta.classList.add(
                "flex",
                "items-center",
                "justify-end",
                "gap-1",
                "text-xs",
                "mt-1"
            );

            const timeEl = document.createElement("span");
            timeEl.textContent = `${t
                .getHours()
                .toString()
                .padStart(2, "0")}:${t
                .getMinutes()
                .toString()
                .padStart(2, "0")}`;

            timeEl.classList.add(isOwn ? "text-blue-100" : "text-gray-500");
            meta.appendChild(timeEl);

            if (isOwn) {
                const readIndicator = document.createElement("span");
                readIndicator.classList.add("read-indicator");
                readIndicator.textContent = isRead ? "✓✓" : "✓";
                readIndicator.style.color = isRead ? "#00FFFF" : "#9ca3af";
                meta.appendChild(readIndicator);
            }

            msgDiv.appendChild(meta);
        }

        wrapper.appendChild(msgDiv);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    };

    // Ambil semua pesan awal dari API
    const loadMessages = async () => {
        try {
            const response = await axios.get(
                `${baseUrl}/ticket-replies/${ticketId}`,
                { headers: { Authorization: `Bearer ${token}` } }
            );

            chatBox.innerHTML = "";

            // Ambil data
            let replies = response.data.data.replies || [];
            let attachments = response.data.data.attachments || [];

            // Normalisasi replies → text bubble
            replies = replies.map((r) => ({
                type: "text",
                message: r.message,
                isOwn: r.is_own,
                time: r.created_at,
                sender_name: r.sender_name,
                isRead: r.is_read,
                attachment: null,
                fileType: null,
            }));

            // Normalisasi attachments → file bubble
            attachments = attachments.map((a) => ({
                type: "file",
                message: null,
                isOwn: a.is_own,
                time: a.created_at,
                sender_name: a.sender_name,
                isRead: false,
                attachment: a.url || `/storage/${a.file_path}`,
                fileType: a.type || a.mime_type,
            }));

            // Gabungkan semua chat
            let all = [...replies, ...attachments];

            // Urutkan berdasarkan created_at
            all.sort((a, b) => new Date(a.time) - new Date(b.time));

            // Render semua pesan sesuai urutan
            all.forEach((msg) => {
                addMessage(
                    msg.message,
                    msg.isOwn,
                    msg.time,
                    msg.sender_name,
                    msg.isRead,
                    msg.attachment,
                    msg.fileType
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

    // Event submit form chat
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
        Swal.fire({
            title: "Yakin ingin Akhiri Tiket ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, akhiri",
            cancelButtonText: "Batal",
        }).then(async (result) => {
            if (!result.isConfirmed) {
                console.log("Dibatalkan");
                return;
            }

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
                console.error("❌ Gagal mengakhiri tiket:", error);
            }
        });
    });

    window.Echo.channel(`ticket.${ticketId}`)
        .listen(".TicketReplied", async (e) => {
            console.log("💬 New message from user:", e);

            // tampilkan pesan user ke admin
            if (e.user_id !== token) {
                addMessage(e.message, false, e.created_at, e.sender_name);

                // 👁 tandai sebagai dibaca langsung (tanpa refresh)
                try {
                    await axios.get(`${baseUrl}/ticket-replies/${ticketId}`, {
                        headers: { Authorization: `Bearer ${token}` },
                    });
                    console.log("👁 Pesan user ditandai dibaca oleh admin.");
                } catch (err) {
                    console.error("❌ Gagal menandai dibaca:", err);
                }
            }
        })
        .listen(".TicketStatusUpdated", (e) => {
            if (e.status === "closed") {
                // 🔥 1. Sembunyikan tombol end chat
                if (endChatBtn) {
                    endChatBtn.style.display = "none";
                }

                // 🔥 2. Update status label
                if (chatStatusLabel) {
                    chatStatusLabel.style.display = "inline-block";
                    chatStatusLabel.textContent = "Tiket Ditutup";
                    chatStatusLabel.classList.add("closed-status");
                }

                // 🔥 3. Disabled input chat
                const msgInput = document.querySelector("#messageInput");
                const sendBtn = document.querySelector("#sendBtn");

                if (msgInput) msgInput.disabled = true;
                if (sendBtn) sendBtn.disabled = true;

                // ubah tampilan supaya terlihat "readonly"
                if (msgInput) msgInput.placeholder = "Chat ini telah ditutup";

                // 🔥 4. Tambahkan class agar chat box terlihat nonaktif (opsional)
                const chatBox = document.querySelector(".chat-box");
                if (chatBox) chatBox.classList.add("chat-closed");

                // 🔥 5. Alert info biar admin tau
                Swal.fire({
                    icon: "info",
                    title: "User telah menutup chat",
                    timer: 1800,
                    showConfirmButton: false,
                });
            }
        })
        .listen(".TicketRead", (e) => {
            console.log("👁 Pesan sudah dibaca oleh user:", e.userId);
            const allOwnMessages = chatBox.querySelectorAll(".message-own");
            allOwnMessages.forEach((msg) => {
                const check = msg.querySelector(".read-indicator");
                if (check) {
                    check.textContent = "✓✓";
                    check.style.color = "#00FFFF"; // hijau
                }
            });
        });

    updateChatUI(statusSlug);
    loadMessages().then(() => {
        console.log("✅ Messages loaded, now listening TicketRead...");
    });
});
