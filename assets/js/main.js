// MarketLink - global interactions

document.addEventListener('DOMContentLoaded', function () {
  initNavSearch();
  initCartDrawer();
  initMobileMenu();
});

function initMobileMenu() {
  const menuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');

  if (!menuBtn || !mobileMenu) return;

  function openMenu() {
    mobileMenu.classList.add('open');
    menuBtn.setAttribute('aria-expanded', 'true');
  }

  function closeMenu() {
    mobileMenu.classList.remove('open');
    menuBtn.setAttribute('aria-expanded', 'false');
  }

  menuBtn.addEventListener('click', function () {
    mobileMenu.classList.contains('open') ? closeMenu() : openMenu();
  });

  // Escape closes the dropdown
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && mobileMenu.classList.contains('open')) closeMenu();
  });

  // Clicking outside the dropdown (or on a link) closes it
  document.addEventListener('click', function (e) {
    if (!mobileMenu.classList.contains('open')) return;
    if (!mobileMenu.contains(e.target) && !menuBtn.contains(e.target)) closeMenu();
  });
}

function initNavSearch() {
  const searchBtn = document.getElementById('searchBtn');
  const navSearch = document.getElementById('navSearch');

  if (!searchBtn || !navSearch) return;

  const searchInput = document.getElementById('navSearchInput');
  const nav = searchBtn.closest('nav');

  function openSearch() {
    navSearch.classList.add('open');
    searchBtn.setAttribute('aria-expanded', 'true');
    searchInput.focus();
  }

  function closeSearch() {
    navSearch.classList.remove('open');
    searchBtn.setAttribute('aria-expanded', 'false');
  }

  searchBtn.addEventListener('click', function () {
    navSearch.classList.contains('open') ? closeSearch() : openSearch();
  });

  // Escape closes and returns focus to the icon
  navSearch.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeSearch();
      searchBtn.focus();
    }
  });

  // Clicking anywhere outside the bar collapses it
  document.addEventListener('click', function (e) {
    if (!navSearch.classList.contains('open')) return;
    if (!navSearch.contains(e.target) && !searchBtn.contains(e.target)) closeSearch();
  });
}

function initCartDrawer() {
  const cartBtn = document.getElementById('cartBtn');
  const cartOverlay = document.getElementById('cartOverlay');
  const cartClose = document.getElementById('cartClose');

  if (!cartBtn || !cartOverlay) return;

  const drawer = cartOverlay.querySelector('.cart-drawer');
  let closeTimer = null;

  function openCart() {
    clearTimeout(closeTimer);
    cartOverlay.hidden = false;
    document.body.classList.add('cart-open');
    // Next frame so the transition runs from opacity 0 / translated state
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        cartOverlay.classList.add('open');
        cartBtn.setAttribute('aria-expanded', 'true');
        if (cartClose) cartClose.focus();
      });
    });
  }

  function closeCart() {
    cartOverlay.classList.remove('open');
    cartBtn.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('cart-open');
    closeTimer = setTimeout(function () {
      cartOverlay.hidden = true;
    }, 350);
    cartBtn.focus();
  }

  cartBtn.addEventListener('click', openCart);

  if (cartClose) cartClose.addEventListener('click', closeCart);

  // Close when clicking the empty area outside the drawer panel
  cartOverlay.addEventListener('click', function (e) {
    if (!drawer.contains(e.target)) closeCart();
  });

  // Close with Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !cartOverlay.hidden) closeCart();
  });
}
