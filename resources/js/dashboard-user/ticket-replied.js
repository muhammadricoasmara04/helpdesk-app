import axios from "axios";
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

    window.Echo.channel(`ticket.${ticketId}`).listen(".TicketReplied", (e) => {
        console.log("💬 New reply:", e);
        // Kalau bukan own message, tampilkan
        if (e.user_id !== token) {
            // optional: cek sendiri
            addMessage(e.message, false, e.created_at, e.sender_name);
        }
    });
    loadMessages();
});
