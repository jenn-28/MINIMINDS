   // Colores disponibles
   const colores = ["#F5F297","#AEF797", "#97D7F7", "#A297F7"]; 
   const coloresT = ["#FCF757", "#278908", "#0074AE", "#5D49F3"];
   // Función para cambiar el color del fondo
   function cambiarColorFondo() {
       // Obtener un color aleatorio de la lista de colores
       const nuevoColor = colores[Math.floor(Math.random() * colores.length)];
       // Aplicar el nuevo color al fondo del cuerpo
       document.body.style.backgroundColor = nuevoColor;
       
    }

    function cambiarColorTexto(){
      const nuevoColor = coloresT[Math.floor(Math.random() * coloresT.length)];

      document.querySelector('.datos-usuario').style.color = nuevoColor;

    }
   
   // Cambiar el color del fondo cada 3 segundos (3000 milisegundos)
   setInterval(cambiarColorFondo, 3000);
   setInterval(cambiarColorTexto, 3500);