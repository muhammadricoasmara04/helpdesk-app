import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const tableBody = document.getElementById("tickets-table-body");
    let tickets = [];
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

            tickets = response.data?.data || [];

            if (response.data.success && tickets.length > 0) {
                renderTickets(tickets);
            } else {
                setMessageRow("⚠️ Belum ada tiket yang kamu buat.");
            }
        } catch (error) {
            console.error("❌ Error ambil data tiket:", error);
            setMessageRow("Terjadi kesalahan saat mengambil data tiket.");
        }
    }

    function renderTickets(ticketsToRender) {
        tableBody.innerHTML = "";

        if (!ticketsToRender.length) {
            setMessageRow("⚠️ Tidak ada tiket yang cocok dengan filter.");
            return;
        }

        ticketsToRender.forEach((data, index) => {
            const row = document.createElement("tr");
            row.classList.add(
                index % 2 === 0 ? "bg-white" : "bg-gray-300",
                "transition-colors",
                "duration-200"
            );

            // Status badge
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

            // Priority badge
            const prioritySlug = data.priority?.slug?.toLowerCase() || "-";
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

            let actionButton = "";

            if (data.status?.slug?.toLowerCase() === "closed") {
                // Kalau tiket sudah close
                actionButton = `
        <a href="/dashboard/user/ticket-reply/${data.id}" class="inline-flex items-center gap-1 bg-gray-400 text-white text-xs font-medium px-3 py-1.5 rounded-md">
            Closed
        </a>
    `;
            } else {
                // Kalau belum close
                actionButton = `
        <a href="/dashboard/user/ticket-reply/${data.id}"
           class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-md hover:bg-blue-700 transition">
           💬 Chat
        </a>
    `;
            }

            row.innerHTML = `
            <td class="px-5 py-3 font-medium text-gray-800">${
                data.ticket_code || "-"
            }</td>
            <td class="px-5 py-3">${data.subject || "-"}</td>
            <td class="px-5 py-3">${statusBadge}</td>
            
            <td class="px-5 py-3">${
                data.application?.application_name || "-"
            }</td>
            <td class="px-5 py-3">${data.problem?.problem_name || "-"}</td>
            <td class="px-5 py-3">${data.employee_name || "-"}</td>
            <td class="px-5 py-3 text-gray-500">${new Date(
                data.created_at
            ).toLocaleString()}</td>
           <td class="px-5 py-3 text-center">
    ${actionButton}
</td>
        `;
            tableBody.appendChild(row);
        });
    }

    function filterTickets() {
        const searchValue = document
            .getElementById("search-input")
            .value.toLowerCase();
        const statusValue = document
            .getElementById("status-filter")
            .value.toLowerCase();
        const priorityValue = document
            .getElementById("priority-filter")
            .value.toLowerCase();

        const filtered = tickets.filter((t) => {
            const matchSearch =
                t.subject?.toLowerCase().includes(searchValue) ||
                t.ticket_code?.toLowerCase().includes(searchValue) ||
                t.employee_name?.toLowerCase().includes(searchValue);

            const matchStatus =
                !statusValue || t.status?.slug?.toLowerCase() === statusValue;
            const matchPriority =
                !priorityValue ||
                t.priority?.slug?.toLowerCase() === priorityValue;

            return matchSearch && matchStatus && matchPriority;
        });

        renderTickets(filtered);
    }
    function initFilters() {
        const searchInput = document.getElementById("search-input");
        const statusFilter = document.getElementById("status-filter");
        const priorityFilter = document.getElementById("priority-filter");
        const resetButton = document.getElementById("reset-filter");

        // Event: search input
        searchInput?.addEventListener("input", filterTickets);

        // Event: dropdown filter
        statusFilter?.addEventListener("change", filterTickets);
        priorityFilter?.addEventListener("change", filterTickets);

        // Event: reset filter
        resetButton?.addEventListener("click", () => {
            searchInput.value = "";
            statusFilter.value = "";
            priorityFilter.value = "";
            renderTickets(tickets);
        });
    }

    getMyTickets();
    initFilters();
});
