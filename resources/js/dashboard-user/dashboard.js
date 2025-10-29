import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    console.log("📦 Ticket page loaded");

    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const tableBody = document.getElementById("tickets-table-body");

    const setMessageRow = (message) => {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-gray-500">${message}</td>
            </tr>
        `;
    };

    async function getMyTickets() {
        try {
            const token = localStorage.getItem("token");
            if (!token) {
                setMessageRow("❌ Belum login. Silakan login terlebih dahulu.");
                return;
            }

            const response = await axios.get(`${baseUrl}/user/tickets`, {
                headers: { Authorization: `Bearer ${token}` },
            });

            const tickets = response.data?.data || [];

            if (response.data.success && tickets.length > 0) {
                tableBody.innerHTML = "";

                tickets.forEach((data) => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td class="border px-4 py-2">${
                            data.ticket_code || "-"
                        }</td>
                        <td class="border px-4 py-2">${data.subject || "-"}</td>
                        <td class="border px-4 py-2">${
                            data.status?.name || "-"
                        }</td>
                        <td class="border px-4 py-2">${
                            data.priority?.name || "-"
                        }</td>
                        <td class="border px-4 py-2">${
                            data.application?.application_name || "-"
                        }</td>
                        <td class="border px-4 py-2">${
                            data.problem?.problem_name || "-"
                        }</td>
                        <td class="border px-4 py-2">${
                            data.employee_name || "-"
                        }</td>
                        <td class="border px-4 py-2">${new Date(
                            data.created_at
                        ).toLocaleString()}</td>
                        <td class="border px-4 py-2 text-center">
        <a href="/ticket-reply/${data.id}"
           class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
            💬 Chat
        </a>
    </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                setMessageRow("⚠️ Belum ada tiket yang kamu buat.");
            }
        } catch (error) {
            console.error("❌ Error ambil data tiket:", error);
            setMessageRow("Terjadi kesalahan saat mengambil data tiket.");
        }
    }

    getMyTickets();
});
