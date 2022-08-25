window.onload= function() {
    let elementos= document.getElementsByClassName("buttonPlus");
    for (let i=0; i<elementos.length; i++ ) {
        elementos[i].addEventListener("click", (event) => {
            var valores= new Array();
            var id= document.getElementById("product_idModal");
            var name= document.getElementById("product_nameModal");
            var price= document.getElementById("product_priceModal");
            var size= document.getElementById("inventory_sizeModal");
            var gender= document.getElementById("inventory_genderModal");
            var quantity= document.getElementById("quantityModal");
            var elementosTD= event.srcElement.parentElement.getElementsByTagName("td");
            for (let i=0; i<elementosTD.length; i++) {
                valores.push(elementosTD[i].innerHTML);
            }

            id.value= valores[0];;
            name.value= valores[1];
            price.value= valores[2];
            size.value= valores[3];
            gender.value= valores[4];
            quantity.value=valores[5];
        });
    }


    let elementBtn= document.getElementsByClassName("btnDelete");
     for (let i=0; i<elementBtn.length; i++ ) {
        elementBtn[i].addEventListener("click", (event) => {
            var id= document.getElementsByName("product_id[]");
            var gender= document.getElementsByName("inventory_gender[]");
            var size= document.getElementsByName("inventory_size[]");
            var quantity= document.getElementsByName("quantity[]");
            
            var inputId= document.getElementById("product_idModalDelete");
            var inputSize= document.getElementById("inventory_sizeModalDelete");
            var inputGender= document.getElementById("inventory_genderModalDelete");
            var inputQuantity= document.getElementById("quantity_modalDelete");
            inputId.value= id[i].value;
            inputSize.value= size[i].value;
            inputGender.value= gender[i].value;
            inputQuantity.value= quantity[i].value;
           
        });
    }


}
