import "./bootstrap"
import Alpine from "alpinejs"

window.Alpine = Alpine

Alpine.start()

// OCPP Dashboard utilities
window.OCPPDashboard = {
    formatDuration: (startTime, endTime = null) => {
        const start = new Date(startTime)
        const end = endTime ? new Date(endTime) : new Date()
        const diff = Math.abs(end - start)

        const hours = Math.floor(diff / (1000 * 60 * 60))
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))

        if (hours > 0) {
            return `${hours}h ${minutes}m`
        }
        return `${minutes}m`
    },

    formatEnergy: (wh) => {
        if (wh >= 1000) {
            return `${(wh / 1000).toFixed(1)} kWh`
        }
        return `${wh} Wh`
    },

    getStatusColor: (status) => {
        const colors = {
            Available: "status-available",
            Charging: "status-charging",
            Occupied: "status-charging",
            Faulted: "status-offline",
            Unavailable: "status-offline",
        }
        return colors[status] || "status-offline"
    },
}
