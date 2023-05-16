const overlay_container = document.querySelector(".overlays");

function openOverlay (overlay_id) {
    overlay_container.style.zIndex = "1000";
    overlay_container.style.backgroundColor = "#000000bb";
    document.getElementById(overlay_id).style.display = "block";
    document.getElementById(overlay_id).style.filter = "opacity(1)";
}

function closeOverlay (overlay_id) {
    overlay_container.style.zIndex = "-3";
    overlay_container.style.backgroundColor = "transparent";
    if(overlay_id) { document.getElementById(overlay_id).style.display = "none"; document.getElementById(overlay_id).style.filter = "opacity(0)"; }
    else document.querySelectorAll(".overlay").forEach(overlay => { overlay.style.display = "none"; overlay.style.filter = "opacity(0)"; });
}

overlay_container.addEventListener("click", (e) => { if (e.target == overlay_container) closeOverlay() });