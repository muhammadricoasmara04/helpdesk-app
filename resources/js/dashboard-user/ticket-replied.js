// resources/js/dashboard-user/ticket-replied.js
import axios from "axios";
import "../echo"; // pastikan path ke echo.js sesuai lokasi kamu

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

    // Helper function untuk menampilkan pesan di UI
    const addMessage = (message, isOwn = false) => {
        const msgDiv = document.createElement("div");

        const baseClasses = ["my-2", "p-2", "rounded", "max-w-[75%]"];
        const variantClasses = isOwn
            ? ["ml-auto", "bg-blue-500", "text-white"]
            : ["bg-gray-200", "text-black"];

        msgDiv.classList.add(...baseClasses, ...variantClasses);
        msgDiv.textContent = message;
        chatBox.appendChild(msgDiv);
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
            replies.forEach((reply) => addMessage(reply.message, reply.is_own));
        } catch (error) {
            console.error("❌ Gagal memuat pesan:", error);
            chatBox.innerHTML =
                '<div class="text-center text-gray-500 mt-5">Gagal memuat pesan.</div>';
        }
    };

    // Kirim pesan baru ke API
    const sendMessage = async (message) => {
        try {
            await axios.post(
                `${baseUrl}/ticket-replies`,
                { ticket_id: ticketId, message },
                { headers: { Authorization: `Bearer ${token}` } }
            );
            addMessage(message, true);
            messageInput.value = "";
        } catch (error) {
            console.error("❌ Gagal mengirim pesan:", error);
        }
    };

    // Event listener untuk submit form
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) sendMessage(message);
    });

    window.Echo.private(`ticket.${ticketId}`).listen(".TicketReplied", (e) => {
        console.log("📨 Pesan baru diterima:", e);
        addMessage(e.message);
    });
    // Load pesan pertama kali
    loadMessages();
});
