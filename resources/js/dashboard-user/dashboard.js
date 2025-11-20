import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";

    const tableBody = document.getElementById("tickets-table-body");
    const paginationContainer = document.getElementById("pagination");

    let currentPage = 1;
    let lastPage = 1;
    let tickets = [];

    const setMessageRow = (message) => {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4 text-gray-500">${message}</td>
            </tr>
        `;
    };

    // ========== PAGINATION UI ==========
    function renderPagination() {
        paginationContainer.innerHTML = "";

        // Prev button
        const prevBtn = document.createElement("button");
        prevBtn.textContent = "Prev";
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = "px-3 py-1 border rounded";
        prevBtn.onclick = () => getMyTickets(currentPage - 1);
        paginationContainer.appendChild(prevBtn);

        // Number buttons
        for (let i = 1; i <= lastPage; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.className = `px-3 py-1 border rounded ${
                i === currentPage ? "bg-blue-600 text-white" : ""
            }`;
            btn.onclick = () => getMyTickets(i);
            paginationContainer.appendChild(btn);
        }

        // Next button
        const nextBtn = document.createElement("button");
        nextBtn.textContent = "Next";
        nextBtn.disabled = currentPage === lastPage;
        nextBtn.className = "px-3 py-1 border rounded";
        nextBtn.onclick = () => getMyTickets(currentPage + 1);
        paginationContainer.appendChild(nextBtn);
    }

    // ========== FETCH DATA ==========
    async function getMyTickets(page = 1) {
        try {
            const token = localStorage.getItem("token");

            const searchValue = document.getElementById("search-input").value;
            const statusValue = document.getElementById("status-filter").value;
            const priorityValue =
                document.getElementById("priority-filter").value;

            const response = await axios.get(`${baseUrl}/user/tickets`, {
                headers: { Authorization: `Bearer ${token}` },
                params: {
                    page,
                    per_page: 10,
                    search: searchValue,
                    status: statusValue,
                    priority: priorityValue,
                },
            });

            tickets = response.data.data;
            currentPage = response.data.pagination.current_page;
            lastPage = response.data.pagination.last_page;

            renderTickets(tickets);
            renderPagination();
        } catch (error) {
            console.error(error);
            setMessageRow("❌ Gagal memuat data tiket.");
        }
    }

    // ========== RENDER TABEL ==========
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

            // Status Badge
            const statusSlug = data.status?.slug?.toLowerCase() || "-";
            let statusBadge = "";
            switch (statusSlug) {
                case "open":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">Open</span>`;
                    break;
                case "in-progress":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">In Progress</span>`;
                    break;
                case "closed":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-400 text-white whitespace-nowrap">Closed</span>`;
                    break;
                case "pending":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 whitespace-nowrap">Pending</span>`;
                    break;
                default:
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600 whitespace-nowrap">-</span>`;
            }

            // Priority Badge
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
                    priorityBadge = `<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600">-</span>`;
            }

            // Action Button
            let actionButton = "";
            if (statusSlug === "closed") {
                actionButton = `
                    <a href="/dashboard/user/ticket-reply/${data.id}" 
                       class="inline-flex items-center gap-1 bg-gray-400 text-white text-xs font-medium px-3 py-1.5 rounded-md">
                        Closed
                    </a>`;
            } else {
                actionButton = `
                    <a href="/dashboard/user/ticket-reply/${data.id}"
                       class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-md hover:bg-blue-700 transition relative">
                       💬 Chat
                       ${
                           data.has_unread
                               ? `<span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>`
                               : ""
                       }
                    </a>`;
            }

            // Table row
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
                <td class="px-5 py-3 text-center">${actionButton}</td>
            `;

            tableBody.appendChild(row);
        });
    }

    // ========== FILTER HANDLERS ==========
    function initFilters() {
        const searchInput = document.getElementById("search-input");
        const statusFilter = document.getElementById("status-filter");
        const priorityFilter = document.getElementById("priority-filter");
        const resetButton = document.getElementById("reset-filter");

        // Live search (auto reload)
        searchInput?.addEventListener("input", () => getMyTickets(1));

        // Dropdown filters
        statusFilter?.addEventListener("change", () => getMyTickets(1));
        priorityFilter?.addEventListener("change", () => getMyTickets(1));

        // Reset filter
        resetButton?.addEventListener("click", () => {
            searchInput.value = "";
            statusFilter.value = "";
            priorityFilter.value = "";
            getMyTickets(1); // Reload all
        });
    }

    getMyTickets();
    initFilters();
});
