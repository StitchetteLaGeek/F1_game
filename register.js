function verification(data){
  const {pseudo, email, mdp} = data;

  if (email && !/^\S+@\S+\.\S+$/.test(email)){
    alert("envoie l'email chef");
    return false;
  }
  if (password && password.length < 6){
    alert("rentre un mot de passe plus fort chef 6 caractères");
    return false;
  }
  if (username && username.trim() === ""){
    alert("panne de pseudo chef");
    return false;
  }
  return true;
}
