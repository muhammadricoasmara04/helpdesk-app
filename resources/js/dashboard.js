import axios from "axios";

document.addEventListener("DOMContentLoaded", async () => {
    const tableBody = document.getElementById("tickets-table-body");
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user"));
    const currentUserId = user?.id;

    function formatDate(dateString) {
        if (!dateString) return "-";
        const date = new Date(dateString);
        return date.toLocaleString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    if (!token) {
        tableBody.innerHTML = `
            <tr><td colspan="9" class="text-center text-red-500">
                Belum login. Silakan login terlebih dahulu.
            </td></tr>`;
        return;
    }

    async function loadTickets() {
        try {
            const response = await axios.get(`${baseUrl}/tickets`, {
                headers: { Authorization: `Bearer ${token}` },
            });

            const tickets = response.data?.data || [];
            renderTickets(tickets);
        } catch (error) {
            console.error("Error saat memuat tiket:", error);
            const message =
                error.response?.data?.message ||
                "Token tidak valid atau sesi login telah berakhir.";
            tableBody.innerHTML = `
                <tr><td colspan="9" class="text-center text-red-500">${message}</td></tr>`;

            if (error.response?.status === 401) {
                localStorage.removeItem("token");
                window.location.href = "/login";
            }
        }
    }

    function renderTickets(tickets) {
        if (!tickets.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-6 text-gray-400 italic">
                        Tidak ada data tiket tersedia.
                    </td>
                </tr>`;
            return;
        }

        tableBody.innerHTML = "";

        tickets.forEach((ticket) => {
            let statusClass = "";
            const statusName = ticket.status?.name || "-";

            switch (statusName.toLowerCase()) {
                case "open":
                    statusClass = "text-blue-600 font-semibold";
                    break;
                case "on progress":
                    statusClass = "text-yellow-500 font-semibold";
                    break;
                case "close":
                    statusClass = "text-red-600 font-semibold";
                    break;
                default:
                    statusClass = "text-gray-600";
            }

            const actionButton = ticket.assigned_to
                ? ticket.assigned_to === currentUserId
                    ? `<a href="dashboard/ticket-reply-admin/${ticket.id}"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                        💬 Chat
                      </a>`
                    : `<span class="bg-yellow-500 text-white px-3 py-1 rounded">
                        ⏳ Sedang Proses
                      </span>`
                : `<button data-id="${ticket.id}"
                    class="assign-btn bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                    🔧 Assign to Me
                  </button>`;

            const row = document.createElement("tr");
            row.classList.add("hover:bg-gray-50");
            row.innerHTML = `
                <td class="px-5 py-3">${ticket.ticket_code || "-"}</td>
                <td class="px-5 py-3">${ticket.subject || "-"}</td>
                <td class="px-5 py-3 ${statusClass}">${statusName}</td>
                <td class="px-5 py-3">${ticket.priority?.name || "-"}</td>
                <td class="px-5 py-3">${
                    ticket.application?.application_name || "-"
                }</td>
                <td class="px-5 py-3">${
                    ticket.problem?.problem_name || "-"
                }</td>
                <td class="px-5 py-3">${ticket.employee_name || "-"}</td>
                <td class="px-5 py-3">${formatDate(ticket.created_at)}</td>
                <td class="px-5 py-3 text-center">${actionButton}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    // 🧩 Event Delegation untuk Assign
    tableBody.addEventListener("click", async (e) => {
        if (e.target.classList.contains("assign-btn")) {
            const btn = e.target;
            const ticketId = btn.dataset.id;

            const confirmAssign = confirm(
                "Apakah Anda yakin ingin meng-assign tiket ini ke Anda?"
            );
            if (!confirmAssign) return;

            try {
                await axios.post(
                    `${baseUrl}/tickets/${ticketId}/assign`,
                    {},
                    {
                        headers: { Authorization: `Bearer ${token}` },
                    }
                );

                // Update tampilan langsung tanpa reload
                btn.outerHTML = `
                    <a href="dashboard/ticket-reply-admin/${ticketId}"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                        💬 Chat
                    </a>
                `;
            } catch (error) {
                console.error(error);
                alert("Gagal meng-assign tiket. Coba lagi.");
                btn.disabled = false;
                btn.textContent = "🔧 Assign to Me";
            }
        }
    });

    // 🔥 Jalankan pertama kali
    await loadTickets();
});
