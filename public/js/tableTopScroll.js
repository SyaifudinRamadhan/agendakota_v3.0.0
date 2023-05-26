// Scroll table controller
function scrollTable(idDummy, idReal) {
    document.getElementById(idDummy).addEventListener('scroll', function () {
        document.getElementById(idReal).scrollLeft = document.getElementById(idDummy).scrollLeft; 
     });
     document.getElementById(idReal).addEventListener('scroll', function () {
         document.getElementById(idDummy).scrollLeft = document.getElementById(idReal).scrollLeft; 
      });
}

// default scroll top table (single table in one page)
document.addEventListener('DOMContentLoaded', function () {
    scrollTable('dummy-table', 'real-table');
});