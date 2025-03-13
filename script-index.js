'use strict';
$(document).ready(function() {

    $('.product-links-wap a').click(function(){
      var this_src = $(this).children('img').attr('src');
      $('#product-detail').attr('src',this_src);
      return false;
    });
});

//visible password login part
const password = document.querySelector("#password");
const form = document.querySelector("form");

const eyeIcon = document.querySelector(".eye-icon");
const passwordField = document.getElementById("password");
const passwordField1 = document.getElementById("password1");

function togglePasswordVisibility() {
  const type =
    passwordField.getAttribute("type") === "password" ? "text" : "password";
  passwordField.setAttribute("type", type);

  eyeIcon.textContent = type === "password" ? "visibility" : "visibility_off";
}

//visible password sign up part
function togglePasswordVisibility1() {
  const type =
    passwordField1.getAttribute("type") === "password" ? "text" : "password";
  passwordField1.setAttribute("type", type);

  eyeIcon.textContent = type === "password" ? "visibility" : "visibility_off";
}

// open sidebar
function openNav() {
  document.getElementById("mySidenav").style.width = "13rem";
}

//close sidebar
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

/* Event listener for advanced search toggle */
document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("advancedSearchToggle")
    .addEventListener("click", function () {
      var advancedSearchOptions = document.getElementById(
        "advancedSearchOptions"
      );
      advancedSearchOptions.style.display =
        advancedSearchOptions.style.display === "block" ? "none" : "block";
    });
});

document.addEventListener("click", function (event) {
  var sidebar = document.getElementById("mySidenav");
  var toggleButton = document.getElementById("navToggleBtn");
  var isClickInsideSidebar = sidebar.contains(event.target);
  var isClickInsideToggleButton = toggleButton.contains(event.target);

  if (!isClickInsideSidebar && !isClickInsideToggleButton) {
    closeNav();
  }
});

//log-in and log-out
document
  .getElementById("navToggleBtn")
  .addEventListener("click", function (event) {
    event.preventDefault();
    openNav();
  });
document.addEventListener("DOMContentLoaded", function () {
  const userIcon = document.getElementById("userDropdown");
  const loginLogoutSection = document.getElementById("loginLogoutSection");

  userIcon.addEventListener("click", function (event) {
    event.preventDefault();

    loginLogoutSection.classList.toggle("show");
  });
});

//modal profile
$(document).ready(function () {
  $(".product-image").click(function () {
    var imageUrl = $(this).attr("src");
    $("#modalImage").attr("src", imageUrl);
    $("#imageModal").modal("show");
  });
});

/*stars rating system*/
const stars = document.querySelectorAll(".star");
const statusEl = document.querySelector(".status");
const defaultRatingIndex = 0;
let currentRatingIndex = 0;

const checkSelectedStar = (star) => {
  if (parseInt(star.getAttribute("data-rate")) === currentRatingIndex) {
    return true;
  } else {
    return false;
  }
};

const setRating = (index) => {
  stars.forEach((star) => star.classList.remove("selected"));
  if (index > 0 && index <= stars.length) {
    document
      .querySelector('[data-rate="' + index + '"]')
      .classList.add("selected");
  }
};

const resetRating = () => {
  currentRatingIndex = defaultRatingIndex;
  setRating(defaultRatingIndex);
};

stars.forEach((star) => {
  star.addEventListener("click", function () {
    if (checkSelectedStar(star)) {
      resetRating();
      return;
    }
    const index = parseInt(star.getAttribute("data-rate"));
    currentRatingIndex = index;
    setRating(index);
  });

  star.addEventListener("mouseover", function () {
    const index = parseInt(star.getAttribute("data-rate"));
    setRating(index);
  });

  star.addEventListener("mouseout", function () {
    setRating(currentRatingIndex);
  });
});

stars.forEach((star) => {
  star.addEventListener("click", function () {
    const rating = this.getAttribute("data-rate");
    document.getElementById("ratingInput").value = rating;
  });
});

//show report over the page
function prepareReportForm(productID, userID) {
  $("#reportedProductID").val(productID);
  $("#reportedUserID").val(userID);
  $("#reportModal").modal("show");
}