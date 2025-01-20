function showAdultsChildrenInput() {
    var adultChildrenInput = document.getElementById("adultos-niños");

    if (adultChildrenInput.style.display === "none") {
        adultChildrenInput.style.display = "block";
    } else {
        adultChildrenInput.style.display = "none";
    }
}

function showAdultsChildrenInput(numHab) {
    for (var i = 0; i < numHab; i++) {
        var adultChildrenInput = document.getElementById("adultos-niños-" + i);

        if (adultChildrenInput.style.display === "none") {
            adultChildrenInput.style.display = "block";
        } else {
            adultChildrenInput.style.display = "none";
        }
    }
}

function dynamicInput() {
    var adultChildrenInput = document.getElementById("adultos-niños");
    var NumHabitaciones = document.getElementById("NumHabitaciones").value;

    if (adultChildrenInput.style.display === "none" && NumHabitaciones > 0) {
        createAdultChildInputFields(NumHabitaciones);
        adultChildrenInput.style.display = "block";
        //show the input fields for as many hab there are
    } 
}

function createAdultChildInputFields(numInputs) {
    const container = document.getElementById('adultos-niños');

    for (let i = 1; i <= numInputs; i++) {
        //Create title from room
        const room = document.createElement('h3');
        room.textContent = `Habitación ${i}`;


        // Create adult label and input
        const adultLabel = document.createElement('label');
        adultLabel.textContent = `Adult ${i}: `;
        const adultInput = document.createElement('input');
        adultInput.type = 'number';
        adultInput.id = `adult${i}`;
        adultInput.name = `adult${i}`;
        adultInput.required = true;
        adultLabel.setAttribute('for', `adult${i}`);
            
        const br = document.createElement('br');

        // Create child label and input
        const childLabel = document.createElement('label');
        childLabel.textContent = `Child ${i}: `;
        const childInput = document.createElement('input');
        childInput.type = 'number';
        childInput.id = `child${i}`;
        childInput.name = `child${i}`;
        childInput.required = true;
        childLabel.setAttribute('for', `child${i}`);
    
        // Append labels and inputs to the container
        container.appendChild(room);

        container.appendChild(adultLabel);
        container.appendChild(adultInput);
        
        container.appendChild(childLabel);
        container.appendChild(childInput);

        container.appendChild(br);
    }

    
}