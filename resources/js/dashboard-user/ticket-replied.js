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
        isRead = false
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

        const text = document.createElement("div");
        text.textContent = message;
        msgDiv.appendChild(text);

        // waktu + centang
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

            const timeEl = document.createElement("span");
            timeEl.textContent = formattedTime;
            metaEl.appendChild(timeEl);

            if (isOwn) {
                const checkEl = document.createElement("span");
                checkEl.classList.add("check-icon");
                checkEl.innerHTML = isRead ? "✓✓" : "✓";
                checkEl.style.color = isRead ? "#00FFFF" : "#9ca3af";
                metaEl.appendChild(checkEl);
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
            const replies = response.data.data || response.data || [];
            replies.forEach((reply) =>
                addMessage(
                    reply.message,
                    reply.is_own,
                    reply.created_at,
                    reply.is_read
                )
            );
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

        if (statusSlug === "closed") {
            Swal.fire({
                icon: "warning",
                title: "Tiket Ditutup",
                text: "Anda tidak dapat mengunggah lampiran karena tiket ini sudah ditutup.",
                confirmButtonText: "OK",
            });
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
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Lampiran berhasil diunggah.",
                timer: 2000,
                showConfirmButton: false,
            });

            // Reload supaya Blade render ulang lampiran baru
            setTimeout(() => {
                location.reload();
            }, 1500);
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "Gagal Mengunggah",
                text: "Terjadi kesalahan saat mengunggah lampiran. Silakan coba lagi.",
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
