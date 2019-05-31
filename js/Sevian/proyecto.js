
function fds(){
var menu1 = new Sevian.Menu({
    target:"que",
    caption:"que menu",
    type:"accordion",
    useButton:true,

    items:[
        {

            caption:"tres",
            action:"alert(this.caption);",
            wCheck:true,
            onCheck:function(a, b){alert(33)}
                
            
        }


    ]

});


menu1.add({

    caption:"uno",
    action:"alert(this.caption);",
    wCheck:true,
    onCheck:function(a, b){alert(11)}
        
    
});

menu1.add({

    caption:"dos",
    action:"alert(this.className);",
    wCheck:true,
    //onCheck:"alert('todo');",
    
});

};
