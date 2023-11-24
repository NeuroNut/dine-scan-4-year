document.addEventListener("DOMContentLoaded", function () {
  const counters = document.querySelectorAll(".counter");

  counters.forEach((counter) => {
    const decrementBtn = counter.querySelector(".decrement");
    const incrementBtn = counter.querySelector(".increment");
    const countSpan = counter.querySelector("span");

    let count = 0;

    decrementBtn.addEventListener("click", () => {
      if (count > 0) {
        count--;
        countSpan.textContent = count;
      }
    });

    incrementBtn.addEventListener("click", () => {
      count++;
      countSpan.textContent = count;
    });
  });
});
