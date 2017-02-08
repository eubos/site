var req;

function processReqChange() {
if (req.readyState == 4){ 
if (req.status == 200){
document.getElementById('counter').innerHTML=req.r esponseText; 
}; 
};
};

function loadXMLDoc(url) { 
if (window.XMLHttpRequest) { 
req = new XMLHttpRequest(); 
req.onreadystatechange = processReqChange; 
req.open("GET", url, true); 
req.send(null); 
} else if (window.ActiveXObject) { 
req = new ActiveXObject("Microsoft.XMLHTTP"); 
if (req) { 
req.onreadystatechange = processReqChange;
req.open("GET", url, true); 
req.send(); 
}; 
};
};

function counter(v){
loadXMLDoc('counter.php'+v); 
}; 
