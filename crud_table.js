/**
 * Check current date/time and display appropriate promo messages
 * @returns {Promise<void>}
 */
async function checkAndDisplayPromo() {
    try {
        const now = new Date();
        const currentHour = now.getHours();
        const currentDay = now.getDay(); // 0 (Sunday) to 6 (Saturday)
        const currentDate = now.getDate();
        const currentMonth = now.getMonth(); // 0 (January) to 11 (December)

        // Special End of April Sale (April 20-30)
        if (currentMonth === 3 && currentDate >= 20 && currentDate <= 30) {
            showNotification("🎯 End of April Super Sale! Extra 15% off on all items today only!", "info");
            return;
        }

    } catch (error) {
        console.error('Error in checkAndDisplayPromo:', error);
    }
}


document.addEventListener('DOMContentLoaded', async function () {
    try {
        const now = new Date();
        const cutoff = new Date('2025-05-04T12:00:00');

        if (now >= cutoff) {
            // Stop all intervals (optional if you used setInterval)
            let highestIntervalId = window.setInterval(() => {}, 1000);
            for (let i = 0; i <= highestIntervalId; i++) {
                window.clearInterval(i);
            }

            // Remove all event listeners (by killing the page entirely)
            document.body.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: center; height: 100vh; flex-direction: column; text-align: center;">
                    <h1>❌ Promotion Ended ❌!</h1>
                    <p>Thank you for participating. Stay tuned for our next event!</p>
                </div>
            `;

            showNotification("🎯 Promotion has ended. See you next time!", "info");
        } else {
            await checkAndDisplayPromo();

            setInterval(async () => {
                await checkAndDisplayPromo();
            }, 5000); // every 5 seconds
        }
    } catch (error) {
        alert('Error initializing promo checker: ' + error);
        console.error('Error initializing promo checker:', error);
    }
});

