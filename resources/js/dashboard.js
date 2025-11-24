import axios from "axios";
import Swal from "sweetalert2";
document.addEventListener("DOMContentLoaded", async () => {
    const tableBody = document.getElementById("tickets-table-body");
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user"));
    const currentUserId = user?.id;
    let tickets = [];
    const paginationContainer = document.getElementById("pagination");
    let currentPage = 1;
    let lastPage = 1;

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

    function renderPagination() {
        paginationContainer.innerHTML = "";

        // Prev button
        const prevBtn = document.createElement("button");
        prevBtn.textContent = "Prev";
        prevBtn.disabled = currentPage === 1;
        prevBtn.className =
            "px-3 py-1 rounded border border-gray-300 bg-white hover:bg-gray-100 disabled:opacity-50";
        prevBtn.addEventListener("click", () => {
            if (currentPage > 1) {
                loadTickets(currentPage - 1);
            }
        });
        paginationContainer.appendChild(prevBtn);

        // Pages
        for (let i = 1; i <= lastPage; i++) {
            const pageBtn = document.createElement("button");
            pageBtn.textContent = i;
            pageBtn.className = `px-3 py-1 rounded border border-gray-300 ${
                i === currentPage
                    ? "bg-blue-600 text-white"
                    : "bg-white hover:bg-gray-100"
            }`;
            pageBtn.addEventListener("click", () => {
                loadTickets(i);
            });
            paginationContainer.appendChild(pageBtn);
        }

        // Next button
        const nextBtn = document.createElement("button");
        nextBtn.textContent = "Next";
        nextBtn.disabled = currentPage === lastPage;
        nextBtn.className =
            "px-3 py-1 rounded border border-gray-300 bg-white hover:bg-gray-100 disabled:opacity-50";
        nextBtn.addEventListener("click", () => {
            if (currentPage < lastPage) {
                loadTickets(currentPage + 1);
            }
        });
        paginationContainer.appendChild(nextBtn);
    }

    async function loadTickets(page = 1) {
        const searchValue = document.getElementById("search-input").value;
        const statusValue = document.getElementById("status-filter").value;
        const priorityValue = document.getElementById("priority-filter").value;

        try {
            const response = await axios.get(`${baseUrl}/tickets`, {
                headers: { Authorization: `Bearer ${token}` },
                params: {
                    page,
                    per_page: 10,
                    search: searchValue,
                    status: statusValue,
                    priority: priorityValue,
                },
            });

            console.log({
                search: searchValue,
                status: statusValue,
                priority: priorityValue,
            });

            tickets = response.data.data.data;
            currentPage = response.data.data.current_page;
            lastPage = response.data.data.last_page;

            renderTickets(tickets);
            renderPagination();
        } catch (error) {
            console.error(error);
        }
    }

    function renderTickets(tickets) {
        const user = JSON.parse(localStorage.getItem("user") || "{}");
        const userRole = user.role || null;
        const staffList = user.staff || [];

        console.log("Role user:", userRole);
        console.log("List staff:", staffList);
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

        tickets.forEach((ticket, index) => {
            // === Zebra color (selang-seling baris)
            const row = document.createElement("tr");
            row.classList.add(
                index % 2 === 0 ? "bg-white" : "bg-gray-50",
                "hover:bg-blue-50",
                "transition-colors",
                "duration-200"
            );

            // === Status badge
            const statusName = (ticket.status?.slug || "-").toLowerCase();
            let statusBadge = "";

            switch (statusName) {
                case "open":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">Open</span>`;
                    break;
                case "in-progress":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">On&nbsp;Progress</span>`;
                    break;
                case "closed":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-400 text-gray-700 whitespace-nowrap">Closed</span>`;
                    break;
                case "pending":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 whitespace-nowrap">Pending</span>`;
                    break;
                default:
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 whitespace-nowrap">-</span>`;
            }

            // === Prioritas badge
            const priorityName = (ticket.priority?.name || "-").toLowerCase();
            let priorityBadge = "";
            switch (priorityName) {
                case "high":
                    priorityBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 whitespace-nowrap">High</span>`;
                    break;
                case "medium":
                    priorityBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700 whitespace-nowrap">Medium</span>`;
                    break;
                case "low":
                    priorityBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 whitespace-nowrap">Low</span>`;
                    break;
                default:
                    priorityBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 whitespace-nowrap">-</span>`;
            }

            let actionButton = "";

            const statusSlug = ticket.status?.slug?.toLowerCase() || "-";

            if (statusSlug === "closed") {
                actionButton = `
        <a href="ticket-reply-admin/${ticket.id}" class="inline-flex items-center gap-1 bg-gray-400 text-white text-xs font-medium px-3 py-1.5 rounded-md">
            Closed
        </a>
    `;
            } else if (ticket.assigned_to) {
                if (ticket.assigned_to === currentUserId) {
                    // Admin yang sedang menangani
                    actionButton = `
           <a href="ticket-reply-admin/${ticket.id}"
   class="relative inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-md hover:bg-blue-700 transition">
    💬 Chat

    ${
        ticket.has_unread
            ? `<span class="absolute -top-1 -right-1 w-3 h-3 bg-red-600 rounded-full animate-pulse"></span>`
            : ""
    }
</a>
`;
                } else {
                    // Tiket sedang di-handle admin lain
                    actionButton = `
            <span class="inline-flex items-center gap-1 bg-yellow-500 text-white text-xs font-medium px-3 py-1.5 rounded-md">
                ⏳ Proses
            </span>`;
                }
            } else {
                // Belum ada admin handle → tampil tombol assign
                actionButton = `
        <div class="relative inline-block text-left">
            <button class="menu-btn px-2 py-1 rounded hover:bg-gray-200" data-id="${ticket.id}">
                ⋮
            </button>

            <div class="menu hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-md z-10" 
                 data-id="${ticket.id}">
                 
                <button class="assign-to-me w-full text-left px-4 py-2 hover:bg-gray-100" data-id="${ticket.id}">
                    Ambil Tiket
                </button>

                <button class="assign-to-staff w-full text-left px-4 py-2 hover:bg-gray-100" data-id="${ticket.id}">
                    Kirim ke Staff
                </button>
            </div>
        </div>
    `;
            }

            // === Susunan tabel ===
            row.innerHTML = `
            <td class="px-5 py-3 font-medium text-gray-800">${
                ticket.ticket_code || "-"
            }</td>
            <td class="px-5 py-3 truncate">${ticket.subject || "-"}</td>
            <td class="px-5 py-3">${statusBadge}</td>
            <td class="px-5 py-3">${
                ticket.application?.application_name || "-"
            }</td>
            <td class="px-5 py-3">${ticket.problem?.problem_name || "-"}</td>
            <td class="px-5 py-3">${ticket.employee_name || "-"}</td>
            <td class="px-5 py-3 text-gray-500">${formatDate(
                ticket.created_at
            )}</td>
             <td class="px-5 py-3 text-gray-500">${formatDate(
                 ticket.updated_at
             )}</td>
            <td class="px-5 py-3 text-center">${actionButton}</td>
        `;

            tableBody.appendChild(row);
        });
    }

    // 🧩 Event Delegation untuk Assign
    tableBody.addEventListener("click", async (e) => {
        // 🟦 Toggle menu titik tiga
        if (e.target.classList.contains("menu-btn")) {
            const id = e.target.dataset.id;
            const menu = document.querySelector(`.menu[data-id="${id}"]`);
            menu.classList.toggle("hidden");
            return;
        }

        // 🟩 Ambil Tiket (Assign to Me)
        if (e.target.classList.contains("assign-to-me")) {
            const ticketId = e.target.dataset.id;

            const result = await Swal.fire({
                title: "Ambil Tiket?",
                text: "Anda akan menangani tiket ini.",
                icon: "question",
                showCancelButton: true,
            });

            if (!result.isConfirmed) return;

            try {
                await axios.post(
                    `${baseUrl}/tickets/${ticketId}/assign`,
                    { staff_id: user.id }, // 👉 kirim ID sendiri
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                window.location.href = `/dashboard/ticket-reply-admin/${ticketId}`;
            } catch (error) {
                Swal.fire("Error", "Gagal mengambil tiket", "error");
            }
            return;
        }

        // 🟨 Kirim ke Staff
        if (e.target.classList.contains("assign-to-staff")) {
            const ticketId = e.target.dataset.id;

            // Staff diambil dari localStorage
            const user = JSON.parse(localStorage.getItem("user") || "{}");
            const staffList = user.staff || [];

            if (staffList.length === 0) {
                Swal.fire(
                    "Tidak ada staff",
                    "Anda belum memiliki staff terdaftar.",
                    "warning"
                );
                return;
            }

            const { value: staffId } = await Swal.fire({
                title: "Delegasikan ke Staff",
                input: "select",
                inputOptions: staffList.reduce((acc, staff) => {
                    acc[staff.id] = staff.name;
                    return acc;
                }, {}),
                inputPlaceholder: "Pilih staff",
                showCancelButton: true,
            });

            if (!staffId) return;

            try {
                await axios.post(
                    `${baseUrl}/tickets/${ticketId}/assign`,
                    { staff_id: staffId }, // 👉 INI SUDAH BENAR
                    { headers: { Authorization: `Bearer ${token}` } }
                );

                Swal.fire(
                    "Berhasil!",
                    "Tiket berhasil didelegasikan.",
                    "success"
                );
                loadTickets();
            } catch (error) {
                Swal.fire("Error", "Gagal mengirim tiket", "error");
            }

            return;
        }
    });

    async function loadStaff() {
        const response = await axios.get(`${baseUrl}/staff`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        const user = JSON.parse(localStorage.getItem("user") || "{}");
        user.staff = response.data.data;
        localStorage.setItem("user", JSON.stringify(user));
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
                t.priority?.name?.toLowerCase() === priorityValue;

            return matchSearch && matchStatus && matchPriority;
        });

        renderTickets(filtered);
    }

    await loadTickets();

    document
        .getElementById("search-input")
        .addEventListener("input", () => loadTickets(1));

    document
        .getElementById("status-filter")
        .addEventListener("change", () => loadTickets(1));

    document
        .getElementById("priority-filter")
        .addEventListener("change", () => loadTickets(1));

    document.getElementById("reset-filter")?.addEventListener("click", () => {
        document.getElementById("search-input").value = "";
        document.getElementById("status-filter").value = "";
        document.getElementById("priority-filter").value = "";

        loadTickets(1);
    });
    window.Echo.channel("tickets.live")
        .listen(".TicketReplied", (e) => {
            console.log("📩 Pesan baru masuk:", e);
            loadTickets(1); // reload ulang data tiket
        })
        .listen(".AttachmentUploaded", (e) => {
            console.log("📎 Attachment baru:", e);
            loadTickets(1);
        });
    loadStaff();
});
