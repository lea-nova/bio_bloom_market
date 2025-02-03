let btns = document.getElementsByClassName("btn-panier");
let inputQuantite = document.getElementsByClassName("input-quantite");

let spanLignePanier = document.getElementsByClassName("ligne-panier");
console.log(btns);
console.log(inputQuantite);
console.log(spanLignePanier);
let indexForLocation;
let numberAComparer = "";

const idItem = (number) => {
  let numberItem = "";
  let results = {};
  for (const spanParLigne of spanLignePanier) {
    let classListParSpan = spanParLigne.classList;
    let classItem = classListParSpan[1];
    let classItemNumber = classItem.split("-");
    console.log(classItemNumber[1]);
    if (number === classItemNumber[1]) {
      let formWhereInputNumberIs = spanParLigne.childNodes[1];
      let valueFromInputNumer = formWhereInputNumberIs[0].value;
      console.log(
        "la valeur pour la quantité présente dans input number",
        formWhereInputNumberIs[0].value
      );
      numberItem = number;
      results = {
        buttonId: numberItem,
        valueInput: valueFromInputNumer,
      };
    }
  }
  return results;

  // console.log(classItem);
  //         console.log(classOfTheSpan[1]) // la valeur que je dois comparer avec celui du btn soit dec soit inc.
};

for (const btn of btns) {
  btn.addEventListener("click", () => {
    let idBtn = btn.id;
    console.log(btn);
    // console.log(idBtn)
    let idBtnArr = idBtn.split("-");
    // console.log(idBtnArr[2])
    let value = idItem(idBtnArr[2]);
    console.log(value.buttonId);
    console.log(idBtn);
    switch (true) {
      case idBtnArr[0] === "decrement":
        console.log(value.valueInput);

        value.valueInput -= 1;
        console.log(value.valueInput);
        console.log(btn.dataset.inputCounterDecrement);
        location.reload()
        break;
      case idBtnArr[0] === "increment":
        console.log(idBtn);
        console.log(btn.dataset.inputCounterIncrement);
        console.log(value.valueInput);

        value.valueInput += 1;
        value.valueInput.textContent = value.valueInput + 1
        console.log(value.valueInput)
        location.reload()
        console.log(value.valueInput);
        console.log(btn.dataset.inputCounterIncrement);
        break;

      default:
        break;
    }
    // console.log(btn.contains(''))
  });
}

console.log("Hello le panier");
