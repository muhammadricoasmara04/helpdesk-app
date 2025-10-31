import axios from "axios";

document.addEventListener("DOMContentLoaded", async () => {
    const tableBody = document.getElementById("tickets-table-body");
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");

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
        tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500">Belum login. Silakan login terlebih dahulu.</td></tr>`;
        return;
    }

    try {
        const response = await axios.get(`${baseUrl}/tickets`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        const tickets = response.data?.data || [];

        if (tickets.length > 0) {
            tableBody.innerHTML = "";
            tickets.forEach((ticket) => {
                // Tentukan class warna untuk status
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

                const row = document.createElement("tr");
                row.classList.add("hover:bg-gray-50"); // hover effect untuk setiap baris
                row.innerHTML = `
            <td class="px-5 py-3">${ticket.ticket_code || "-"}</td>
            <td class="px-5 py-3">${ticket.subject || "-"}</td>
            <td class="px-5 py-3 ${statusClass}">${statusName}</td>
            <td class="px-5 py-3">${ticket.priority?.name || "-"}</td>
            <td class="px-5 py-3">${
                ticket.application?.application_name || "-"
            }</td>
            <td class="px-5 py-3">${ticket.problem?.problem_name || "-"}</td>
            <td class="px-5 py-3">${ticket.employee_name || "-"}</td>
            <td class="px-5 py-3">${formatDate(ticket.created_at)}</td>
            <td class="px-5 py-3 text-center">
                <a href="dashboard/ticket-reply-admin/${ticket.id}"
                   class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                   💬 Chat
                </a>
            </td>
        `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = `
        <tr>
            <td colspan="9" class="text-center py-6 text-gray-400 italic">
                Tidak ada data tiket tersedia.
            </td>
        </tr>
    `;
        }
    } catch (error) {
        console.error("Error saat memuat tiket:", error);
        const message =
            error.response?.data?.message ||
            "Token tidak valid atau sesi login telah berakhir.";
        tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500">${message}</td></tr>`;

        // Jika unauthorized (401), paksa logout
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            window.location.href = "/login";
        }
    }
});
