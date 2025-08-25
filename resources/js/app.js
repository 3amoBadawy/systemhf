import Alpine from 'alpinejs'
import { createIcons } from 'lucide'

// Initialize Alpine.js
window.Alpine = Alpine
Alpine.start()

// Initialize Lucide icons
createIcons()

// Mobile sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle')
    const sidebar = document.getElementById('sidebar')
    const overlay = document.getElementById('sidebar-overlay')
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open')
            if (overlay) {
                overlay.classList.toggle('hidden')
            }
        })
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open')
            overlay.classList.add('hidden')
        })
    }
    
    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open')
            if (overlay) {
                overlay.classList.add('hidden')
            }
        }
    })
})

// Form validation helpers
window.formHelpers = {
    validatePhone: function(input) {
        let value = input.value.replace(/\D/g, '')
        if (value.length > 0 && !value.startsWith('05')) {
            value = '05' + value
        }
        if (value.length > 10) {
            value = value.substring(0, 10)
        }
        input.value = value
    },
    
    showSuccess: function(message) {
        // Create success notification
        const notification = document.createElement('div')
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-down'
        notification.textContent = message
        
        document.body.appendChild(notification)
        
        setTimeout(() => {
            notification.remove()
        }, 3000)
    },
    
    showError: function(message) {
        // Create error notification
        const notification = document.createElement('div')
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-down'
        notification.textContent = message
        
        document.body.appendChild(notification)
        
        setTimeout(() => {
            notification.remove()
        }, 3000)
    }
}

// Table functionality
window.tableHelpers = {
    sortTable: function(tableId, columnIndex) {
        const table = document.getElementById(tableId)
        if (!table) return
        
        const tbody = table.querySelector('tbody')
        const rows = Array.from(tbody.querySelectorAll('tr'))
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim()
            const bValue = b.cells[columnIndex].textContent.trim()
            
            if (aValue < bValue) return -1
            if (aValue > bValue) return 1
            return 0
        })
        
        rows.forEach(row => tbody.appendChild(row))
    },
    
    filterTable: function(tableId, searchTerm) {
        const table = document.getElementById(tableId)
        if (!table) return
        
        const rows = table.querySelectorAll('tbody tr')
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase()
            const match = text.includes(searchTerm.toLowerCase())
            row.style.display = match ? '' : 'none'
        })
    }
}
