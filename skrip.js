let currentLetter = null;

// Opens the editor for a selected letter
function editKey(letter) {
currentLetter = letter;
document.getElementById('editLetter').innerText = letter;
document.getElementById('editValue').value = keyData[letter] ?? '';
document.getElementById('editor').style.display = 'block';
document.getElementById('editValue').focus();
}

// Apply the edited value to UI and update hidden field
function applyChange() {
const newVal = document.getElementById('editValue').value.trim();
if (!currentLetter) return alert('No letter selected.');
if (newVal.length !== 1) return alert('Enter exactly one character.');



}

// Highlight duplicate substitutes in red
function highlightDuplicates() {
const seen = {};
for (const letter in keyData) {
const valEl = document.getElementById('sub_' + letter);
if (!valEl) continue;
const val = valEl.innerText;
if (seen[val]) {
valEl.style.color = 'red';
document.getElementById('sub_' + seen[val]).style.color = 'red';
} else {
valEl.style.color = '#096179';
seen[val] = letter;
}
}
}

// Update the hidden input with current keyData
function updateHiddenField() {
const updatedKey = {};
for (const letter in keyData) {
const valEl = document.getElementById('sub_' + letter);
updatedKey[letter] = valEl ? valEl.innerText : '';
}
document.getElementById('updated_key').value = JSON.stringify(updatedKey);
}

// Initialize duplicate highlighting on page load
document.addEventListener('DOMContentLoaded', () => {
highlightDuplicates();
});
