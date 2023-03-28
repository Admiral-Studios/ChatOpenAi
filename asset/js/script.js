"use strict";

const generateUsername = () => {
  let username = "";
  const possibleChars = "abcdefghijklmnopqrstuvwxyz0123456789";
  for (let i = 0; i < 8; i++) {
    username += possibleChars.charAt(Math.floor(Math.random() * possibleChars.length));
  }
  return username;
}

const userName = generateUsername();
const inputField = document.querySelector('.message-input');
const messagesContainer = document.querySelector('.messages-content');
const submitBtn = document.querySelector('.message-submit');

const insertMessage = (message) => {
  const newMessage = document.createElement('div');
  newMessage.classList.add('message', 'message-personal', 'new');
  newMessage.textContent = message;
  messagesContainer.appendChild(newMessage);

  inputField.value = '';
}

const answerMessage = () => {
  const newMessage = document.createElement('div');
  newMessage.classList.add('message', 'loading', 'new');

  const avatarFigure = document.createElement('figure');
  avatarFigure.classList.add('avatar');
  newMessage.appendChild(avatarFigure);

  const avatarImg = document.createElement('img');
  avatarImg.setAttribute('src', 'https://static.cdnlogo.com/logos/c/38/ChatGPT.svg');
  avatarFigure.appendChild(avatarImg);

  const messageText = document.createElement('span');
  newMessage.appendChild(messageText);

  messagesContainer.appendChild(newMessage);
  submitBtn.disabled = true;
}

const addAnswer = (messageText) => {
  const loadingMessage = document.querySelector('.message.loading.new');
  let newContent = document.createElement('span');
  newContent.innerHTML = messageText;
  loadingMessage.appendChild(newContent);
  loadingMessage.classList.remove('loading');
  submitBtn.disabled = false;
  messagesContainer.scrollBy(0, 100);
}

async function postData(message) {
  answerMessage()
  const formdata = new FormData();
  formdata.append("userName", userName);
  formdata.append("message", message);

  const requestOptions = {
    method: 'POST',
    body: formdata,
    redirect: 'follow'
  };

  try {
    const response = await fetch('https://chatopenai.admiral-studios.com/gpt.php', requestOptions);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const result = await response.json();

    let resultMessage = result.body.choices[0].message.content;
    if (resultMessage.includes('\n')) {
      resultMessage = resultMessage.replace(/\n/g, '<br>');
    }

    addAnswer(resultMessage)
  } catch (error) {
    console.error('Error:', error);
    addAnswer(error)
  }
}

submitBtn.addEventListener('click', () => {
  let messageText = inputField.value.trim();
  if (messageText === '') {
    return false;
  }

  insertMessage(messageText)
  postData(messageText)
})

document.addEventListener('keypress', function(e) {
  if (e.keyCode === 13 || e.key === 'Enter') {
    e.preventDefault();
    submitBtn.click();
  }
});