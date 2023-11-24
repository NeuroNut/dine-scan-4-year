const allStar = document.querySelectorAll('.rating .star')
const ratingValue = document.querySelector('.rating input')
const feedbackText = document.getElementById('feedbackText')
const submitbtn = document.getElementById('feedback_submit')

const feedbackSection = document.getElementById('feedback-section')
const feedbackThanks = document.getElementById('feedback-thanks')

allStar.forEach((item, idx)=> {
	item.addEventListener('click', function () {
		let click = 0
		ratingValue.value = idx + 1
        console.log(ratingValue.value);

		allStar.forEach(i=> {
			i.classList.replace('bxs-star', 'bx-star')
			i.classList.remove('active')
		})
		for(let i=0; i<allStar.length; i++) {
			if(i <= idx) {
				allStar[i].classList.replace('bx-star', 'bxs-star')
				allStar[i].classList.add('active')
			} else {
				allStar[i].style.setProperty('--i', click)
				click++
			}
		}
	})
})

//create a click event for submit button
submitbtn.addEventListener('click', function () {
    let order = {};
    

    const xhr = new XMLHttpRequest();
        xhr.open('GET', 'getLastOrderSummary.php?mobileNumber=' + mobileNumber);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.length > 0) {
                    order = response[0];
                    //console.log("Order:", order);
                    const newFeedback = {
                        rating: ratingValue.value,
                        feedback: feedbackText.value,
                        mobile_number: mobileNumber,
                        payment_mode: order.payment_mode,
                        total_amount: order.total_amount
                    };
                    console.log("New Feedback:", newFeedback);
                    saveToDatabase(newFeedback);
                }
            } else {
                console.log('Error occurred while fetching order summary.');
            }
        };
        xhr.send();
})

async function saveToDatabase(feedback) {
    try {
      const response = await fetch("save_feedback.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
          },
        body: JSON.stringify(feedback),
      });
  
      return await response.json();
    } catch (error) {
      console.error("Error saving data to the database:", error);
      return { success: false };
    }
  }


function toggleFeedbackMessage() {
    feedbackSection.classList.toggle('hidden')
    feedbackThanks.classList.toggle('hidden')
}