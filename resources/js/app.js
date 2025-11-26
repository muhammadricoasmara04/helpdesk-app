import "./bootstrap";

import {
    showLoading,
    hideLoading,
    showTableLoading,
    hideTableLoading,
} from "./utils/loading";

window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.showTableLoading = showTableLoading;
window.hideTableLoading = hideTableLoading;
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();
