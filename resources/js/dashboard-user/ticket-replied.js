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

    const addMessage = (message, isOwn = false, time = null) => {
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

        msgDiv.style.maxWidth = "75%";
        msgDiv.style.width = "fit-content";
        const text = document.createElement("div");
        text.textContent = message;
        msgDiv.appendChild(text);

        if (time) {
            const timeEl = document.createElement("div");
            timeEl.classList.add(
                "text-xs",
                isOwn ? "text-blue-100" : "text-gray-500",
                "text-right",
                "mt-1"
            );
            // format waktu jadi jam:menit misal 13:45
            const t = new Date(time);
            timeEl.textContent = `${t
                .getHours()
                .toString()
                .padStart(2, "0")}:${t
                .getMinutes()
                .toString()
                .padStart(2, "0")}`;
            msgDiv.appendChild(timeEl);
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
                addMessage(reply.message, reply.is_own, reply.created_at)
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

            addMessage(message, true, time);
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
        if (!confirm("Yakin ingin mengakhiri chat ini?")) return;

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
            }
        } catch (error) {
            console.error("❌ Gagal mengakhiri chat:", error);
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
        .listen(".TicketReplied", (e) => {
            console.log("💬 New reply:", e);

            if (e.user_id !== token) {
                addMessage(e.message, false, e.created_at, e.sender_name);
            }
        })
        .listen(".TicketStatusUpdated", (e) => {
            console.log("💬 Ticket status updated:", e.status);
            if (e.status === "closed") {
                if (endChatBtn) endChatBtn.style.display = "none";
                if (chatStatusLabel)
                    chatStatusLabel.style.display = "inline-block";
            }
        });
    updateChatUI(statusSlug);
    loadMessages();
});
