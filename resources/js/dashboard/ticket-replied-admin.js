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
        isRead = false
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
        if (isOwn) {
            msgDiv.classList.add("message-own"); // Tambahkan ini!
        }

        msgDiv.style.maxWidth = "75%";
        msgDiv.style.width = "fit-content";

        if (sender && !isOwn) {
            const senderEl = document.createElement("div");
            senderEl.classList.add("text-xs", "text-gray-500", "mb-1");
            senderEl.textContent = sender;
            msgDiv.appendChild(senderEl);
        }

        const text = document.createElement("div");
        text.textContent = message;
        msgDiv.appendChild(text);

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

            // jam
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
            // hanya untuk pesan sendiri
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
                    null,
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
            title: "Yakin ingin mengakhiri chat ini?",
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
                }
            } catch (error) {
                console.error("❌ Gagal mengakhiri chat:", error);
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
                    chatStatusLabel.textContent = "Chat Ditutup";
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
