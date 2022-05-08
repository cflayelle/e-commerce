const starsForm = [...document.querySelectorAll(".rate-form .bi-star"),...document.querySelectorAll(".rate-form .bi-star-fill")] 
const inputRate = document.querySelector("#input-rate")

for (let star of starsForm) {
    star.addEventListener("mouseover", function () {
        resetStars()
        // this.style.color = "#FAB303"
        this.classList.add("bi-star-fill")
        this.classList.remove("bi-star")

        let previousStar = this.previousSibling;

        while ([...previousStar?.classList]?.includes("bi-star")) {
            // previousStar.style.color = "#FAB303"
            previousStar.classList.add("bi-star-fill")
            previousStar.classList.remove("bi-star")

            previousStar = previousStar.previousSibling;
        }
    })

    star.addEventListener("click", function () {
        inputRate.value = this.dataset.value
    })

    star.addEventListener("mouseout", function () {
        resetStars(inputRate.value)
    })
}

function resetStars(note = 0) {
    for (let star of starsForm) {
        if (star.dataset.value > note){
            // star.style.color = "var(--primary)"
            star.classList.remove("bi-star-fill")
            star.classList.add("bi-star")
        }
        else {
            // star.style.color = "#FAB303"
            star.classList.add("bi-star-fill")
            star.classList.remove("bi-star")
        }
    }
    
}