let sum = document.getElementById("sum");
sum.addEventListener("click", () => {
    
    event.preventDefault(); //Fa in modo tale che non venga "rispettato" il comportamento predefinito del form --> i dati non vengono inviati
    
    let num1 = parseFloat(document.getElementById("Val1").value);
    let num2 = parseFloat(document.getElementById("Val2").value);

    if (isNaN(num1) || isNaN(num2)) {
        alert("Per favore, inserisci solo numeri.");
        return;
    }

    document.getElementById("Res").value = num1 + num2;

});

let sub = document.getElementById("sub");
sub.addEventListener("click", () => {
    
    event.preventDefault(); //Fa in modo tale che non venga "rispettato" il comportamento predefinito del form --> i dati non vengono inviati
    
    let num1 = parseFloat(document.getElementById("Val1").value);
    let num2 = parseFloat(document.getElementById("Val2").value);

    if (isNaN(num1) || isNaN(num2)) {
        alert("Per favore, inserisci solo numeri.");
        return;
    }

    document.getElementById("Res").value = num1 - num2;
});

let mult = document.getElementById("mult");
mult.addEventListener("click", () => {
	
    event.preventDefault(); //Fa in modo tale che non venga "rispettato" il comportamento predefinito del form --> i dati non vengono inviati

    let num1 = parseFloat(document.getElementById("Val1").value);
    let num2 = parseFloat(document.getElementById("Val2").value);

    if (isNaN(num1) || isNaN(num2)) {
        alert("Per favore, inserisci solo numeri.");
        return;
    }

    document.getElementById("Res").value = num1 * num2;
});

let divi = document.getElementById("divi");
divi.addEventListener("click", () => {
	
    event.preventDefault(); //Fa in modo tale che non venga "rispettato" il comportamento predefinito del form --> i dati non vengono inviati

    let num1 = parseFloat(document.getElementById("Val1").value);
    let num2 = parseFloat(document.getElementById("Val2").value);

    if (isNaN(num1) || isNaN(num2)) {
        alert("Per favore, inserisci solo numeri.");
        return;
    }

    if(num2 == 0){
        window.alert("Non si può dividere per zero");
    }

    document.getElementById("Res").value = num1 / num2;
});