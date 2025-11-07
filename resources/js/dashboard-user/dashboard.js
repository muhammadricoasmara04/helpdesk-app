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

                tickets.forEach((data, index) => {
                    const row = document.createElement("tr");
                    row.classList.add(
                        index % 2 === 0 ? "bg-white" : "bg-gray-300",

                        "transition-colors",
                        "duration-200"
                    );

                    // Tentukan warna status (badge)
                    const statusSlug = data.status?.slug?.toLowerCase() || "-";
                    let statusBadge = "";

                    switch (statusSlug) {
                        case "open":
                            statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">Open</span>`;
                            break;
                        case "in-progress":
                            statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">In&nbsp;Progress</span>`;
                            break;
                        case "closed":
                            statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-400 text-gray-700 whitespace-nowrap">Closed</span>`;
                            break;
                        default:
                            statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 whitespace-nowrap">-</span>`;
                    }

                    const prioritySlug =
                        data.priority?.slug?.toLowerCase() || "-";
                    let priorityBadge = "";
                    switch (prioritySlug) {
                        case "high":
                            priorityBadge = `<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">High</span>`;
                            break;
                        case "medium":
                            priorityBadge = `<span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700">Medium</span>`;
                            break;
                        case "low":
                            priorityBadge = `<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Low</span>`;
                            break;
                        default:
                            priorityBadge = `<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">-</span>`;
                    }

                    row.innerHTML = `
                    <td class="px-5 py-3 font-medium text-gray-800">${
                        data.ticket_code || "-"
                    }</td>
                    <td class="px-5 py-3">${data.subject || "-"}</td>
                    <td class="px-5 py-3">${statusBadge}</td>
                    <td class="px-5 py-3">${priorityBadge}</td>
                    <td class="px-5 py-3">${
                        data.application?.application_name || "-"
                    }</td>
                    <td class="px-5 py-3">${
                        data.problem?.problem_name || "-"
                    }</td>
                    <td class="px-5 py-3">${data.employee_name || "-"}</td>
                    <td class="px-5 py-3 text-gray-500">${new Date(
                        data.created_at
                    ).toLocaleString()}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="/dashboard/user/ticket-reply/${data.id}"
                           class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-md hover:bg-blue-700 transition">
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
