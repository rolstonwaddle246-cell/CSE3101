function updateTime() {
    const now = new Date();

    // Only show day, date, month, year, and time in 12-hour format
    const options = { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
    };

    document.getElementById('currentTime').textContent = now.toLocaleString('en-US', options);
}

// Update every second
setInterval(updateTime, 1000);
updateTime(); // initial call
