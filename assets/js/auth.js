// assets/js/auth.js
// Simple tab switching for login/register
// Keeps behavior minimal and easy to understand for students.

(function(){
  function switchTab(tabName) {
    var tabs = document.querySelectorAll('.tab-content');
    var buttons = document.querySelectorAll('.tab-button');

    tabs.forEach(function(t){
      t.style.display = (t.id === tabName) ? 'block' : 'none';
    });

    buttons.forEach(function(b){
      var active = b.getAttribute('data-tab') === tabName;
      b.classList.toggle('active', active);
      b.setAttribute('aria-selected', active ? 'true' : 'false');
    });
  }

  // Attach click handlers
  document.addEventListener('DOMContentLoaded', function(){
    var buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(function(b){
      b.addEventListener('click', function(){
        var tab = b.getAttribute('data-tab');
        switchTab(tab);
      });
    });
  });

  // Expose to global for progressive enhancement
  window.switchTab = switchTab;
})();