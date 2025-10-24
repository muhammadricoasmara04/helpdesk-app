import axios from "axios";

document.addEventListener("DOMContentLoaded", async () => {
    const tableBody = document.getElementById("tickets-table-body");
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";

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

    try {
        const token = localStorage.getItem("token");

        if (!token) {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500">Belum login. Silakan login terlebih dahulu.</td></tr>`;
            return;
        }

        // Request data tiket
        const response = await axios.get(`${baseUrl}/tickets`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        const tickets = response.data?.data || [];

        if (response.data.success && tickets.length > 0) {
            tableBody.innerHTML = "";
            tickets.forEach((ticket) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${ticket.ticked_code || "-"}</td>
                    <td>${ticket.subject || "-"}</td>
                    <td>${ticket.status?.name || "-"}</td>
                    <td>${ticket.priority?.name || "-"}</td>
                    <td>${ticket.application?.application_name || "-"}</td>
                    <td>${ticket.problem?.problem_name || "-"}</td>
                    <td>${ticket.employee_name || "-"}</td>
                    <td>${formatDate(ticket.created_at)}</td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = `<tr><td colspan="8" class="text-center">Tidak ada data tiket tersedia.</td></tr>`;
        }
    } catch (error) {
        console.error("Error saat memuat tiket:", error);
        tableBody.innerHTML = `<tr><td colspan="8" class="text-center text-red-500">Gagal memuat data tiket: ${error.message}</td></tr>`;
    }
});
