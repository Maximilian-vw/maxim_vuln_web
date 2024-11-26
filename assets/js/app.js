// Toggle sidebar
const menuIcon = document.querySelector('.menu-icon');
const sideView = document.querySelector('.side-view');

menuIcon.addEventListener('click', () => {
  sideView.classList.toggle('active');
  menuIcon.classList.toggle('active');
});

// Add event listener to toggle menu icon
menuIcon.addEventListener('click', () => {
  if (menuIcon.classList.contains('active')) {
    menuIcon.innerHTML = '<i class="fas fa-bars"></i>'; // Show hamburger icon
  } else {
    menuIcon.innerHTML = '<i class="fas fa-times"></i>'; // Show close icon
  }
});

// Add event listener to collapse or expand sidebar
const collapseButton = document.createElement('button');
collapseButton.textContent = 'Collapse';
collapseButton.className = 'collapse-btn';
sideView.querySelector('nav').appendChild(collapseButton);

collapseButton.addEventListener('click', () => {
  sideView.classList.toggle('active');
  collapseButton.textContent = collapseButton.textContent === 'Collapse' ? 'Expand' : 'Collapse';
});

// Add event listeners to sidebar links
sideView.querySelectorAll('nav ul li a').forEach((link) => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const sectionId = link.getAttribute('href').replace('#', '');
    document.querySelector(.${sectionId}).scrollIntoView({ behavior: 'smooth' });
  });
});

// Add event listeners to product and user cards
document.querySelectorAll('.products-values, .users-values').forEach((card) => {
  card.addEventListener('click', (e) => {
    const cardId = card.getAttribute('data-id');
    // Do something with the card ID, e.g. show a modal or redirect to a detail page
    console.log(Card ${cardId} clicked);
  });
});

// Add event listeners to new product and user forms
document.querySelectorAll('.new--productCreate__form, .new--UserCreate__form').forEach((form) => {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    // Do something with the form data, e.g. send an AJAX request to create a new product or user
    console.log(formData);
  });
});

// Add event listeners to update product and user forms
document.querySelectorAll('.update--product__form, .update--user__form').forEach((form) => {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    // Do something with the form data, e.g. send an AJAX request to update a product or user
    console.log(formData);
  });
});

// Add event listeners to delete product and user buttons
document.querySelectorAll('.products-values .delete-btn, .users-values .delete-btn').forEach((btn) => {
  btn.addEventListener('click', (e) => {
    const cardId = btn.getAttribute('data-id');
    // Do something with the card ID, e.g. send an AJAX request to delete a product or user
    console.log(Delete button ${cardId} clicked);
  });
});

//
const currentTime = document.querySelector('.current-time');

setInterval(() => {
  const now = new Date();
  const time = now.toLocaleTimeString();
  const date = now.toLocaleDateString();
  currentTime.textContent = ${date} ${time};
}, 1000);
//
