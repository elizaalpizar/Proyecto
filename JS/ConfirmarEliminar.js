// ConfirmarEliminar.js - Script para confirmar eliminación de atletas
document.addEventListener('DOMContentLoaded', function() {
    const formsEliminar = document.querySelectorAll('.formEliminar');
    
    formsEliminar.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('¿Estás seguro de que deseas eliminar este atleta? Esta acción no se puede deshacer.')) {
                this.submit();
            }
        });
    });
});
