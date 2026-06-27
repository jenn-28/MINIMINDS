$(document).ready(function(){
  var rows = 4, columns = 4; 
  var pieces = "";
  for(var i=0, top=0; i<rows; i++, top-=100) {
      for(var j=0, left=0; j<columns; j++, left-=100){ 
          pieces += "<div style='background-position:" + left + "px " + top + "px;' class='piece'></div>";
      }  
  }
  $("#pluzzeContainer").html(pieces);
  $("#btnStart").click(function(){ // Cambié "btnStart" a "#btnStart" para usar el selector de id correcto
      var pieces = $("#pluzzeContainer div");
      pieces.each(function(){
          var leftPosition = Math.floor(Math.random()*290) + "px";
          var topPosition = Math.floor(Math.random()*290) + "px";
          $(this).addClass("draggablePiece").css({
              position:"absolute",
              left:leftPosition,
              top:topPosition
          });
          $("#pieceContainer").append($(this));
      });
      var emptyString = ""
      for(var i=0; i<rows; i++) {
          for(var j=0; j<columns; j++){
              emptyString += "<div style='background-image:none' class='piece droppableSpace'></div>";
          }  
      }
      $("#puzzleContainer").html(emptyString); // Cambié "puzzleContainer" a "#puzzleContainer" para usar el selector de id correcto
      $(this).hide();
      $("#btnReset").show()
      implementLogic()
  });
  function implementLogic(){
    $(".draggablePiece").draggable();
    $(".droppableSpace").droppable({
      hoverClass:"ui-state-highlight",
      drop:function(event, ui){
        var draggableElemnet = ui.draggable;
        var droppedOn = $(this);
        droppedOn.addClass("piecePresent");
        $(draggableElemnet).addClass("droppedPiece")
        .css({
          top:0, left:0, position:"relative"
        }).appendTo(droppedOn);
      }
    });
  }
});
