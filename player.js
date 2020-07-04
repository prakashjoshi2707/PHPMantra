/*
    Javascript for activity_starter
    */
"use strict";
var ClassRoom = {
  url: Application.getPath() + 'ClassRoom/getContent',
  videoPlayer:null,
  init: function init() {
    this.bindListener();
  },
  bindListener: function bindListener() {
    this.handleListener();
  },
  videoPlayer:async function () {
    //instance of video player
    var player = videojs('videoPlayer');
    //instance of button
    var button = videojs.getComponent('Button');
    //Playlist in JSON
    var lecture=[];
    var myQuestions=null;
    //For navigation and length of lecture
    var next=0;
    
    let Response = await fetch(ClassRoom.url)
    if (Response.ok) { // if HTTP-status is 200-299
      // get the response body (the method explained below)
      let result = await Response.json();
      // this.createHeader();
      // lecture=result;
      for (var i = 0; i < result.length; i++) {
        console.log(result[i]["chapterNo"], result[i]["title"], result[i]["ebook"]);
        this.createTitle(result[i]["chapterNo"], result[i]["title"], result[i]["ebook"]);
        this.createContent(result[i]["lecture"]);
        for(var j=0;j<result[i]["lecture"].length;j++){
          lecture.push(result[i]["lecture"][j]);
        }
      }
    } else {
      alert("HTTP-Error: " + response.status);
    }
    console.log("Lecture",lecture);
    var totalLectures=lecture.length;
    //Close Button 
    var closeButton = videojs.extend(button, {
      constructor: function() {
        button.apply(this, arguments);
        this.controlText("Exit Course");
        this.addClass('vjs-icon-cog');
      },
      handleClick: function() {
        // this.player().dispose();
      }
    });
    //Previous Button
    var prevButton = videojs.extend(button, {    
      constructor: function() {
        button.apply(this, arguments);
        this.addClass('vjs-icon-previous-item');
        this.controlText('Previous');
      },
      handleClick: function(e) {
        // player.playlist.previous();
      }
    });
    //Next Button
    var nextButton = videojs.extend(button, {    
      constructor: function() {
        button.apply(this, arguments);
        this.addClass('vjs-icon-next-item');
        this.controlText('Next');
      },
      handleClick: function(e) {
        // player.playlist.next();
        // this.controlText('Next (part 3)');
      }
    });

    //Registration in component
    videojs.registerComponent('closeButton', closeButton);
    videojs.registerComponent('prevButton', prevButton);
    videojs.registerComponent('nextButton', nextButton);
  
    //Player child setup
    player.getChild('controlBar').addChild('prevButton', {},0);
    player.getChild('controlBar').addChild('nextButton', {}, 2);
    player.getChild('controlBar').addChild('closeButton', {});
    //=================================================================================================
    //Event to check audio player
    videojs.getPlayer('videoPlayer').ready(function() {
      var myPlayer = this;
      myPlayer.getChild('bigPlayButton').controlText('Play Audio Now!');

       //To display title in the video view
       var textDisplay = document.createElement('p');
       textDisplay.id="textDisplay";
       textDisplay.className = 'uil uil-play-circle vjs-text';
       // textDisplay.innerHTML = lecture[0]["title"];
       player.el().appendChild(textDisplay);


      //fetching the video from playlist and auto play
      if(lecture[0]["content"]=="video"){
         myPlayer.src({src: lecture[0]["file"]});
          $(".lecture-container").first().css("background-color","#ffecec");
          textDisplay.innerHTML = lecture[0]["title"];
          $("#lectureOverview").html(lecture[0]["description"])
          myPlayer.play();
      }

     

      //Big Previous Button left Side
      var bigPreviousButton = document.createElement('p');
      bigPreviousButton.id="bigPreviousButton";
      bigPreviousButton.className = 'bigPreviousButton fas fa-chevron-left';
      bigPreviousButton.innerHTML = "";
      player.el().appendChild(bigPreviousButton);


      //Big Next Button Right Side
      var bigNextButton = document.createElement('p');
      bigNextButton.id="bigNextButton";
      bigNextButton.className = 'bigNextButton fas fa-chevron-right';
      bigNextButton.innerHTML = "";
      player.el().appendChild(bigNextButton);
      //Event for next and previous 
      bigNextButton.addEventListener("click",function(){
        if(next!==totalLectures-1){
          next++;
          contentNavigation(next);
        }else{
          alert("No more next");
          bigNextButton.style.display = "none";
        }
      });

      $(document).on("click","#bSkipSection",function(event){
        event.preventDefault();
        next++;
        alert(next);
        myPlayer.show();
        $("#cardAssignment").hide();
        $("#cardQuiz").hide();
        myPlayer.src({src: lecture[next]["file"]});
        $(".lecture-container").eq(next).css("background-color","#ffecec");
        textDisplay.innerHTML = lecture[next]["title"];
        myPlayer.play();
      });

      bigPreviousButton.addEventListener("click", function(){
        if(next!==0){
          next--;
          textDisplay.innerHTML = lecture[next]["title"];
          myPlayer.src({src: lecture[next]["file"]});
          myPlayer.play();
          bigNextButton.style.display = "block";
          }else{
            alert("No more previous");
            bigPreviousButton.style.display = "none";
          }
      });
 
      //end of play 
      player.on('ended', function() {
      if(next!==totalLectures-1){
        next++;
        if( lecture[next]["content"]=="video"){
          textDisplay.innerHTML = lecture[next]["title"];
          myPlayer.src({src: lecture[next]["file"]});
          myPlayer.play();
          $(".lecture-container").eq(next).css("background-color","#ffecec");
          bigPreviousButton.style.display = "block";
          player.show();
          $("#cardAssignment").hide();
        }else if(lecture[next]["content"]=="assignment"){
          player.pause();
          player.hide();
          $(".lecture-container").eq(next).css("background-color","#ffecec");
          $("#cardAssignment").show();
          $("#bAssignment").attr("href",lecture[next]["file"]);
        }
      }
    });

    }); 
    //On Click to the play video from list
    $(document).on("click",".lecture-video-link",function(event){
      event.preventDefault();
      // textDisplay.innerHTML = $(this).attr("title");
      var next2=$(this).attr("id");
      // player.src({src: $(this).attr("href")});
      // $(".lecture-container",this).css("background-color","#ffecec");
      // player.play();
      contentNavigation(next2);
    });
    
    function contentNavigation(next1){
      if( lecture[next1]["content"]=="video"){
        textDisplay.innerHTML = lecture[next1]["title"];
        player.src({src: lecture[next1]["file"]});
        player.play();
        $(".lecture-container").eq(next1).css("background-color","#ffecec");
        bigPreviousButton.style.display = "block";
        player.show();
        $("#cardAssignment").hide();
        $("#cardQuiz").hide();
      }else if(lecture[next1]["content"]=="assignment"){
        player.pause();
        player.hide();
        $("#cardAssignment").show();
        $("#cardQuiz").hide();
        $(".lecture-container").eq(next1).css("background-color","#ffecec");
        $("#bAssignment").attr("href",lecture[next1]["file"]);
      }else if(lecture[next1]["content"]=="quiz"){
        player.pause();
        player.hide();
        $("#cardAssignment").hide();
        $("#cardQuiz").show();
        $(".lecture-container").eq(next1).css("background-color","#ffecec");
      }
    }

    // To disable right click
    videoPlayer.addEventListener('contextmenu', function (e) { 
      // do something here... 
      e.preventDefault(); 
    }, false);  
  
  },

  createTitle: function (chapterNo, title, ebook) {
    var title = '<a href="'+ebook+'" target="_blank" class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">' +
      '<div class="section-header-left">' +
      '<span class="section-title-wrapper">' +
      '<i class="uil uil-presentation-play crse_icon"></i>' +
      '<span class="section-title-text">' + title+ '</span> ' +
      '</span>' +
      '</div>' +
      '<div class="section-header-right">'+
     
      '<span class="section-header-length">Note</span>'+
												'</div>'+
      '</a>';
    $("#accordion").append(title);
  },

  createContent: function (content) {
    var contentTop = '<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom">';
    var contentBody = '';
    for (var i = 0; i < content.length; i++) {
      var icon = content[i]["content"] == "video" ? "uil uil-play-circle" : content[i]["content"]=="assignment"?"uil uil-file":"uil uil-book-open";
      contentBody = contentBody + '<a href="' + content[i]["file"] + '" class="lecture-video-link" id="'+i+'" title="'+content[i]["title"]+'">' +
        '<div class="lecture-container">' +
        '<div class="left-content">' +
        '<i class="' + icon + ' icon_142"></i>' +
        '<div class="top">' +
        '<div class="title">' + content[i]["title"] + '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</a>';
    }

    var contentBottom = '</div>';
    $("#accordion").append(contentTop + contentBody + contentBottom);
    console.log("=================");
    console.log(content);
  },
  createFooter: function () {
    var footer = '</div>';

  },
  // getRoomContent: async function () {

  //   let Response = await fetch(ClassRoom.url)
  //   if (Response.ok) { // if HTTP-status is 200-299
  //     // get the response body (the method explained below)
  //     let result = await Response.json();
  //     // this.createHeader();
  //     for (var i = 0; i < result.length; i++) {
  //       console.log(result[i]["chapterNo"], result[i]["title"], result[i]["ebook"]);
  //       this.createTitle(result[i]["chapterNo"], result[i]["title"], result[i]["ebook"]);
  //       this.createContent(result[i]["lecture"]);
  //     }


  //   } else {
  //     alert("HTTP-Error: " + response.status);
  //   }
  // },

  handleListener: function handleListener() {
   
    this.videoPlayer();
    // this.getRoomContent();
    this.url;
  }
};
ClassRoom.init();
