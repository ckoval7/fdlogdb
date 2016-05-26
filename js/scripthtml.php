<?php echo '
<script>
function openNav() {
    document.getElementById("drawer").style.width = "150px";
}
function closeNav() {
    document.getElementById("drawer").style.width = "0";
}
	function startTime() {
		var today = new Date();
		var h = today.getUTCHours();
		var m = today.getMinutes();
		var s = today.getSeconds();
		m = checkTime(m);
		s = checkTime(s);
		document.getElementById(\'time\').innerHTML =
		h + ":" + m + ":" + s + " UTC";
		var t = setTimeout(startTime, 500);
	}
	function checkTime(i) {
		if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
		return i;
	}

</script>';
?>