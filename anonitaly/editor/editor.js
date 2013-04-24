// Script made by zypp0
// http://mybeat.it
// AnonItaly : http://mybeat.it/AnonItaly

function insTag(tag) {
obj=document.getElementById('post');
if(obj.selectionEnd){
	var from=obj.selectionStart;
	var to=obj.selectionEnd;
	var selText=obj.value.substring(from,to);
}
    var MyTextarea = document.getElementById('post');
    if (document.all)
    {
        MyTextarea.focus();
        var MyRange = document.selection.createRange();
        MyRange.colapse;
		switch(tag) {
			case 'link':
				var url = selecturl();
				if(url != "") {
					MyRange.text = "[" + tag + "=" + url + "]" + selText + "[/" + tag + "]";
				}
			break;
			case 'size':
				var dimen = selectsize();
				if(dimen != "") {
					MyRange.text = "[" + tag + "=" + dimen + "]" + selText + "[/" + tag + "]";
				}
			break;
			default:
				MyRange.text = "[" + tag + "]" + selText + "[/" + tag + "]";
			break;
		}
    }
    else if (MyTextarea.selectionEnd)
    {
        var MyLength = MyTextarea.textLength;
        var StartSelection = MyTextarea.selectionStart;
        var EndSelection = MyTextarea.selectionEnd;
		switch(tag) {
			case 'link':
				var url = selecturl();
				if(url != "") {
					MyTextarea.value = MyTextarea.value.substring(0, StartSelection) + "[" + tag + "=" + url + "]" + selText + "[/" + tag + "]" + MyTextarea.value.substring(EndSelection, MyLength);
				}
			break;
			case 'size':
				var dimen = selectsize();
				if(dimen != "") {
					MyTextarea.value = MyTextarea.value.substring(0, StartSelection) + "[" + tag + "=" +dimen + "]" + selText + "[/" + tag + "]" + MyTextarea.value.substring(EndSelection, MyLength);
				}
			break;
			default:
				MyTextarea.value = MyTextarea.value.substring(0, StartSelection) + "[" + tag + "]" + selText + "[/" + tag + "]" + MyTextarea.value.substring(EndSelection, MyLength);
			break;
		}
    }
    else
    {
		switch(tag) {
			case 'link':
				var url = selecturl();
				if(url != "") {
					MyTextarea.value += "[" + tag + "=" + url + "]" + selText + "[/" + tag + "]";
				}
			break;
			case 'size':
				var dimen = selectsize();
				if(dimen != "") {
					MyTextarea.value += "[" + tag + "=" + dimen + "]" + selText + "[/" + tag + "]";
				}
			break;
			case 'img':
				var imageurl = selectimageurl();
				if(imageurl != "") {
					MyTextarea.value = MyTextarea.value.substring(0, StartSelection) + "[" + tag + "]" + imageurl + "[/" + tag + "]" + MyTextarea.value.substring(EndSelection, MyLength);
				}
			break;
			default:
				MyTextarea.value += "[" + tag + "]" + selText + "[/" + tag + "]";
			break;
		}
    }
}

function selectsize() {
	var size = prompt("Size?","In pixels");
	if(!isNaN(size) && size != "") {
		return size;
	} else {
		alert('Insert a valid size!');
		return false;
	}
}

function selecturl() {
	var url = prompt("Url?","http://www.");
	if(url != "") {
		var RE = /^http:\/\/(www\.)?[a-zA-Z0-9-]{3,}\.[a-zA-Z]{2,}(\/)?$/;
		if (RE.test(url) == false) {
			alert('Insert a valid URL with "http://www." first of all');
			return false;
		} else {
			return url;
		}
	} else {
		alert('Insert a valid URL!');
		return false;
	}
}

function selectimageurl() {
	var imageurl = prompt("Url of image?","http://www.");
	if(imageurl != "") {
			var checkjpg = imageurl.substr(-4,4);
			var checkpng = imageurl.substr(-4,4);
			var checkgif = imageurl.substr(-4,4);
			var checkhttp = imageurl.substr(0,7);
			if(checkjpg != ".jpg" && checkpng != ".png" && checkgif != ".gif") {
				alert('Invalid image URL');
				return false;
			} else {
				if(checkhttp == "http://") {
					return imageurl;
				} else {
					alert('Insert a valid URL with "http://www." first of all');
				}
			}
	} else {
		alert('Insert a valid URL!');
		return false;
	}
}