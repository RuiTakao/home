const url_flg = document.getElementsByName('url_flg');
const image_flg = document.getElementsByName('image_flg');

const url_input = document.getElementById('url_input');
const image_name_input = document.getElementById('image_name_input');
const image_path_input = document.getElementById('image_path_input');

url_flg.forEach(input => {
  input.addEventListener('input', () => {
    if (input.value == '0') {
      url_input.classList.add('is-hidden');
    } else {
      url_input.classList.remove('is-hidden');
    }
  });
});

image_flg.forEach(input => {
  input.addEventListener('input', () => {
    if (input.value == '0') {
      image_name_input.classList.add('is-hidden');
      image_path_input.classList.add('is-hidden');
    } else {
      image_name_input.classList.remove('is-hidden');
      image_path_input.classList.remove('is-hidden');
    }
  });
});