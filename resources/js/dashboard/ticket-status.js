import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    getTicketStatus();
    storeTicketStatus();
});

async function getTicketStatus() {
    const baseUrl = import.meta.env.VITE_API_BASE_URL;
    const tableBody = document.getElementById("ticketstatus-table-body");

    const showMessage = (message) => {
        tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center py-4 text-gray-500">${message}</td>
            </tr>
        `;
    };

    try {
        const token = localStorage.getItem("token");
        if (!token) {
            showMessage("Belum login. Silakan login terlebih dahulu.");
            return;
        }

        const response = await axios.get(`${baseUrl}/ticket-status`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        const statuses = response.data.data || [];

        if (response.data.success && statuses.length > 0) {
            tableBody.innerHTML = "";
            statuses.forEach((status) => {
                const row = `
                    <tr>
                        <td class="border px-4 py-2">${status.name}</td>
                        <td class="border px-4 py-2">${
                            status.description || "-"
                        }</td>
                        <td class="border px-4 py-2">${status.slug}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            showMessage("Belum ada data Ticket Status.");
        }
    } catch (error) {
        console.error(error);
        showMessage("Gagal memuat data Ticket Status.");
    }
}

function storeTicketStatus() {
    const form = document.getElementById("ticketStatusForm");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const baseUrl = import.meta.env.VITE_API_BASE_URL;
        const token = localStorage.getItem("token");
        const messageEl = document.getElementById("responseMessage");

        if (!token) {
            messageEl.textContent =
                "Belum login. Silakan login terlebih dahulu.";
            messageEl.className = "text-red-500";
            return;
        }

        const data = {
            name: document.getElementById("name").value.trim(),
            description: document.getElementById("description").value.trim(),
        };

        try {
            messageEl.textContent = "Menyimpan...";
            messageEl.className = "text-gray-500";

            const res = await axios.post(`${baseUrl}/ticket-status`, data, {
                headers: { Authorization: `Bearer ${token}` },
            });

            if (res.data.success) {
                messageEl.textContent =
                    "✅ Ticket Status berhasil ditambahkan!";
                messageEl.className = "text-green-600";
                form.reset();
            } else {
                messageEl.textContent = "❌ Gagal menambahkan data.";
                messageEl.className = "text-red-500";
            }
        } catch (error) {
            console.error(error);
            messageEl.textContent = "Terjadi kesalahan saat menyimpan.";
            messageEl.className = "text-red-500";
        }
    });
}
