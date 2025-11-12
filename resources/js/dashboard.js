import axios from "axios";

document.addEventListener("DOMContentLoaded", async () => {
    const tableBody = document.getElementById("tickets-table-body");
    const baseUrl =
        import.meta.env.VITE_API_BASE_URL || "http://127.0.0.1:8000/api";
    const token = localStorage.getItem("token");
    const user = JSON.parse(localStorage.getItem("user"));
    const currentUserId = user?.id;
    let tickets = [];
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

            tickets = response.data?.data || [];
            console.log("🟢 Data tiket dari API:", tickets);
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
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-400 text-gray-700 whitespace-nowrap">Closed</span>`;
                    break;
                case "resolved":
                    statusBadge = `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 whitespace-nowrap">Resolved</span>`;
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

            // === Tombol Aksi (kondisional)
            let actionButton = "";

            const statusSlug = ticket.status?.slug?.toLowerCase() || "-";

            // 🔒 Jika tiket sudah closed
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
               class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-md hover:bg-blue-700 transition">
                💬 Chat
            </a>`;
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
        <button data-id="${ticket.id}"
                class="assign-btn inline-flex items-center gap-1 bg-green-600 text-white text-xs font-medium px-3 py-1.5 rounded-md hover:bg-green-700 transition">
            🔧 Assign to Me
        </button>`;
            }

            // === Susunan tabel ===
            row.innerHTML = `
            <td class="px-5 py-3 font-medium text-gray-800">${
                ticket.ticket_code || "-"
            }</td>
            <td class="px-5 py-3">${ticket.subject || "-"}</td>
            <td class="px-5 py-3">${statusBadge}</td>
            <td class="px-5 py-3">${priorityBadge}</td>
            <td class="px-5 py-3">${
                ticket.application?.application_name || "-"
            }</td>
            <td class="px-5 py-3">${ticket.problem?.problem_name || "-"}</td>
            <td class="px-5 py-3">${ticket.employee_name || "-"}</td>
            <td class="px-5 py-3 text-gray-500">${formatDate(
                ticket.created_at
            )}</td>
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
                    <a href="ticket-reply-admin/${ticketId}"
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

    // 🔥 Jalankan pertama kali
    await loadTickets();

    document
        .getElementById("search-input")
        ?.addEventListener("input", filterTickets);
    document
        .getElementById("status-filter")
        ?.addEventListener("change", filterTickets);
    document
        .getElementById("priority-filter")
        ?.addEventListener("change", filterTickets);
    document.getElementById("reset-filter")?.addEventListener("click", () => {
        document.getElementById("search-input").value = "";
        document.getElementById("status-filter").value = "";
        document.getElementById("priority-filter").value = "";
        renderTickets(tickets);
    });
});
