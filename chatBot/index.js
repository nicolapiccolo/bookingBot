// See https://github.com/dialogflow/dialogflow-fulfillment-nodejs
// for Dialogflow fulfillment library docs, samples, and to report issues
'use strict';

const functions = require('firebase-functions');
const admin = require('firebase-admin');
const {WebhookClient} = require('dialogflow-fulfillment');
const {Card,Text, Image, Payload} = require('dialogflow-fulfillment');
const nodemailer = require('nodemailer');


admin.initializeApp({
    credential: admin.credential.applicationDefault(),
    databaseURL: 'ws://booking-1091b.firebaseio.com/'

});

const logo = "https://firebasestorage.googleapis.com/v0/b/booking-1091b.appspot.com/o/logo.png?alt=media&token=a9f0f290-43f9-4bd9-ad01-ca0273f35db7";
const transporter = nodemailer.createTransport({
    host: 'smtp.gmail.com',
    port: 465,
    secure: true,
    auth: {
      user: 'mr.alfred.bot@gmail.com',
      pass: '*******'
    }});




process.env.DEBUG = 'dialogflow:debug'; // enables lib debugging statements

exports.dialogflowFirebaseFulfillment = functions.https.onRequest((request, response) => {
    const agent = new WebhookClient({ request, response });
    console.log('Dialogflow Request headers: ' + JSON.stringify(request.headers));
    console.log('Dialogflow Request body: ' + JSON.stringify(request.body));

    const rootRef = admin.database().ref();
    const bookRef = rootRef.child("/pren");
    const bookRef2 = rootRef.child("/pren/");

    async function getTotSeat(){     
      var snapshot =  await rootRef.child("/tot_seat").once("value");
      console.log(snapshot.val());
      return (snapshot.val());
    }
    async function getClosingTime(){
      var snapshot = await rootRef.child("/time/closing").once("value");
      console.log(snapshot.val());
      return (snapshot.val());
    }
    async function getOpeningTime(){
      var snapshot = await rootRef.child("/time/opening").once("value");
      console.log(snapshot.val());
      return (snapshot.val());
    }
  
    async function getSpan(){     
      var snapshot =  await rootRef.child("/span").once("value");
      console.log(snapshot.val());
      return (snapshot.val());
    }
    
    async function getNomeRistorante(){     
      var snapshot =  await rootRef.child("/nomeRistorante").once("value");
      console.log(snapshot.val());
      return (snapshot.val());
    }
  
  
 
  const PENDING = 'pending';
  const DELETED = 'cancelled';
  
  function getUndo(agent,text){
    var card_undo = {
        "telegram": {
                "text": text,
                "parse_mode": "HTML",
                "reply_markup": {
                    "inline_keyboard": [[
                        {
                            "text": "⬅️ Indietro",
                            "callback_data": "ciao"            
                        } 
                        ]]
                  }
                }
            };
    
    let undo = new Payload(agent.TELEGRAM, card_undo, { sendAsMessage: true, rawPayload: true });
    agent.add(undo);
  }


  function askCard(agent,card){
    let resp = new Payload(agent.TELEGRAM, card, { sendAsMessage: true, rawPayload: true });
    agent.add(resp);
  }

  
  var card_modifica = {
            "telegram": {
                "text": "📝 <b> Cosa vuoi modificare della prenotazione? </b> ",
                "parse_mode": "HTML",
                "reply_markup": {
                    "inline_keyboard": [[
                        {
                            "text": "🍽 Numero Posti",
                            "callback_data": "a1b1"            
                        }, 
                        {
                            "text": "🕓 Orario",
                            "callback_data": "a1b2"            
                        }],
                        [{
                            "text": "Entrambi",
                            "callback_data": "a1b3"            
                        }]
                    ]
                }
                }
            };
    
     var card_note = {
        "telegram": {
                "text": "Vuoi aggiungere delle note alla prenotazione? ",
                "parse_mode": "HTML",
                "reply_markup": {
                    "inline_keyboard": [[
                        {
                            "text": "👍🏽 Si",
                            "callback_data": "notePrenotazione"            
                        },
                        {
                            "text": "👎🏽 No",
                            "callback_data": "none"            
                        }
                        ]]
                  }
                }
            };
  
    async function welcome(agent) {
         const nome = await getNomeRistorante();
         const pl_tl = {
            "telegram": {
                "text": "Benvenuti, sono Mr. Alfred il chatbot del ristorante <b>" + nome + "</b>.\n\nTi aiuterò ad effettuare le prenotazioni in modo semplice" + `\n \n`
                +"Cosa vuoi fare?",
                "parse_mode":"HTML",
                "reply_markup": {
                    "inline_keyboard": [[
                        {
                            "text": "📝 PRENOTA",
                            "callback_data": "prenotazione"            
                        },
                        {
                            "text": "⚙️ MODIFICA",
                            "callback_data": "modifica"            
                        }],        
                        [{
                            "text": "🗑 CANCELLA",
                            "callback_data": "cancella prenotazione"            
                        }],
                        [{
                            "text": "🗓 DISPONIBILITA'",
                            "callback_data": "disponibilita"            
                        }], 
                        [{
                            "text": "📲 MIE PRENOTAZIONI",
                            "callback_data": "le mie prenotazioni"            
                        }],
                        [{
                            "text": "🕓 ORARI RISTORANTE",
                            "callback_data": "orari"            
                        }]                                             
                        ]
                }
                }
          };
        let pl = new Payload(agent.TELEGRAM, pl_tl, { sendAsMessage: true, rawPayload: true });
        //agent.add(new Image(logo));
        agent.add(pl);
    }

    function fallback(agent) {
        agent.add(`I didn't understand`);
        agent.add(`I'm sorry, can you try again?`);
    }
  
    function getDataString(data){
      let date_ob = new Date(data);
      let date = date_ob.getDate().toString();
      let month = (date_ob.getMonth()+1).toString();
      let year = date_ob.getFullYear().toString();
      
      var datastring = date+"/"+month+"/"+year;
      return datastring;
    }
  
    function getTime(data){
      let date_ob = new Date(data);
      let hour = date_ob.getHours()+2;
      let min = date_ob.getMinutes().toString();
      var time = hour.toString();
      if(min != "0") time += (":" + min);
      return time;
    }
  
    
    function getDate(data){
      const off = +120;
      var d = new Date(new Date(data).getTime() + (off *60 * 1000)); //orario richiesta utente

      return d;
  }
  
    async function printPrenotazione(agent, val){
      let data_p = getDataString(val.orario) + " alle " + getTime(val.orario);
   
      var text = "Riepilogo prenotazione N: " + val.id + `\n` + 
                    "🙍🏽‍♂️ Utente: "+ val.customer + `\n` +
                    "🕓 Orario: " + data_p + `\n` +
                    "🍽 Numero posti: " + val.n_posti + `\n` +
                    "📫 Email: "+ val.email + `\n`;
      
      if(val.nota != "") text += ("📝 Nota: " +val.nota);
      
      agent.add(text);

  }
  
    function checkTime(d,open,close){
      let d_open;
      let d_close;
      var [hours,minutes] = open.split(':'); // using ES6 destructuring
      d_open = new Date(d);
      d_open.setHours(hours);
      d_open.setMinutes(minutes);


      var [hours2,minutes2] = close.split(':'); // using ES6 destructuring
      if (hours2>=0){
      //d_close = new Date(d.getFullYear(), d.getMonth(), d.getDay()+1, hours2, minutes2, 0);
        d_close = new Date(d);
        d_close.setDate(d_close.getDate()+1);
        d_close.setHours(hours2);
        d_close.setMinutes(minutes2);

      }
      else{
        d_close = new Date(d);
     
        d_close.setHours(hours2);
        d_close.setMinutes(minutes2);
      }
      
      console.log("orario utente: " + d);
      console.log("orario apertura: " + d_open);
      console.log("orario chiusura: " + d_close);
      
      if(d>=d_open && d<=d_close){
         return true;
      }
      else {
         return false;
      }
      
      
    }
  
    async function read(orario) {
      
        const t = await getTotSeat();
        const check = await getSpan();
        const open = await getOpeningTime();
        const close = await getClosingTime();
      

        let data_user = await getDate(orario);
        let posti_occupati = 0;
      
        let is_ok = await checkTime(data_user,open,close);
        
        if(is_ok){
          console.log("T: " +t);
          console.log("SPAN: " +check);


          var snapshot = await bookRef.once('value');


          if(snapshot.exists()) {
              snapshot.forEach(function(childSnapshot) {  
                  var book = childSnapshot.val();
                  let data_book =  getDate(book.orario); //data prenotazione

                  if(book.status==PENDING && data_book.getFullYear()==data_user.getFullYear() && data_book.getMonth()==data_user.getMonth() && data_book.getDay()==data_user.getDay()){

                      console.log("KEY: "+ snapshot.key);
                      let new_data = new Date(data_user);
                      new_data.setHours(data_user.getHours() - 1);

                      console.log("orario utente: " + data_user.getHours() + data_user.getMinutes());
                      console.log("orario prenotazione: " + data_book.getHours() + data_book.getMinutes());
                      console.log("orario controllo: " + new_data.getHours() + new_data.getMinutes());



                      if(data_book<=data_user && data_book>=new_data){
                          posti_occupati = posti_occupati + book.n_posti;
                          console.log("trovato n_posti: " +book.n_posti +" p_occ: "+ posti_occupati);
                      }
                  }
              });
          console.log("FINE READ");

          let posti_liberi = t - posti_occupati;
          return posti_liberi;
        }
      }
      else{
        return -1; //ristorante chiuso
      }
    }


   
  
    
     async function disponibilita(agent){
        const giorno = agent.parameters.data;
        const orario = agent.parameters.time;

        console.log('giorno: '+ giorno);
        console.log('orario: '+ orario);

      
     
       
        let dd = new Date(orario);
        console.log("orario utente2: " + dd);
     


        let date = new Date(giorno);
        date.setHours(dd.getHours());
        date.setMinutes(dd.getMinutes());
     
   
        console.log('fascia: '+ date);

        let p = await read(date);
        console.log('DISPONIBILITA: '+ p);
        
        let data_p = getDataString(date) + " alle " + getTime(date);
        agent.add('🕓 Fascia scelta: ' + data_p);
        
        var text = '';
        if(p>-1){
          text = '📲 In questa fascia oraria ci sono: <b>' + p + '</b> posti liberi.';
        }
      else{
          text = '❌ In questa fascia orario il ristorante è chiuso. ';
        }
        getUndo(agent,text);
        
       

    
    }

    function formatDate(d1){
      const rr = '+02:00';
      var d7 = d1.toISOString();
      var sp = d7.split('.');

      //console.log(sp);

      var data_string = sp[0]+rr;
      return data_string;
    }
  
    async function getLastID(){
      
      let id = 0;
      
      var snapshot = await bookRef.once("value");
      let n = snapshot.numChildren();
      
      if(n>0) {
        var list = snapshot.val();
        var lastKey = Object.keys(list)[n-1];
        id = snapshot.child(lastKey + "/id").val();
        console.log(lastKey + " " + id);
      }
      
    return id;  
    }
  
    async function test(agent){

    }
    
    
    async function getKeyById(id){ //ritorno la chiave con quell'id 
       var snapshot = await bookRef.once('value');
       let key = -1;
       if(snapshot.exists()) {
          snapshot.forEach(function(childSnapshot) {
            var book = childSnapshot.val();
                if(id==book.id && book.status==PENDING){
                  console.log("trovato " + book.n_posti);
                  console.log("trovato " + childSnapshot.key);

                key = childSnapshot.key;
                 }          
          });
       }
      return key;
    }
  

    async function deleteBook(key){
      
      console.log("DELETE KEY: " + key);
      var childRef = bookRef.child("/"+key);
      childRef.update({
          "status":DELETED
      });
      
    }
  
  
    async function prenota(agent) {
        const name = agent.parameters.name;
        const email = agent.parameters.email;
        const cell = agent.parameters.cell;
        const n_posti = agent.parameters.n_posti;
        const orario = agent.parameters.orario;
        const giorno = agent.parameters.giorno;
    
        
        let d1 = new Date(giorno); //per salvataggio DB
        let d2 = new Date(orario);
        
        d1.setHours(d2.getHours()+2);
        d1.setMinutes(d2.getMinutes());
        
        let d3 = new Date(d1); //per controllo
        d3.setHours(d3.getHours()-2);
      
        let p = await read(d3); 
        if(p>-1){
          let data_string = formatDate(d1);
          let timestamp = formatDate(new Date());
          console.log("posti liberi: " + p);
          console.log("ora777: "+ data_string);

          if(n_posti<=p){
            const nbookref = bookRef.push();
            const newID = await getLastID()+1;
            //const db = admin.database()
            
              
            
            //console.log(data_string,timestamp,n_posti,user,email,cell,newID,PENDING);
            nbookref.set({ 
              orario: data_string,
              time: timestamp,
              n_posti: n_posti,
              customer: name,
              email: email,
              cell: cell,
              id: newID,
              nota: '',
              status: PENDING
            });

            console.log("prenotazione effettuata");
            
            
            
            let key = nbookref.key;
            console.log("kk " +key);
            
          
            
            //let note = new Payload(agent.TELEGRAM, card_note, { sendAsMessage: true, rawPayload: true });
            //agent.add(note);
            
            
            let data_p = getDataString(data_string) + " alle " + getTime(data_string); 
            agent.add(`✅ Prenotazione effettuata per il ` + data_p +`.`);
            getUndo(agent,`📲 Conserva il tuo numero di prenotazione:  ` + newID);
            agent.add(`📝 Vuoi aggiungere delle note? (Si/No)`);

        	const contextData = {'name':'insert','lifespan': 7,'parameters':{'key':key}};
            agent.setContext(contextData);
            
            const obj = "Prenotazione accettata";
            const body = 
              "<p> La tua prenotazione è stata accettata! <\p>" 
             +"<p> Numero prenotazione: "+ newID + " <\p>"
             +"<p> Prenotazione effettuata per il "+ data_p + " <\p>"
             +"<p> Numero posti: "+ n_posti + " <\p>"
             +"<p> Per effettuare eventuali modifiche alla prenotazione, si prega di utilizzare le funzionalità del ChatBot. <\p>";
      
            await sendEmail(obj,body,email);

            
          }
          else{
            let text= `❌ Posti non disponibili, 
                  in questa fascia oraria sono disponibili <b>`+ p + '</b> posti liberi.';
            getUndo(agent,text);
          }
        }
        else{
          let text = '❌ In questa fascia orario il ristorante è chiuso.';
          getUndo(agent,text);

        }
        
      
    }
  
    async function getKeybyEmail(email,id){
      var snapshot = await bookRef.once('value');
      let key = -1;
      let timestamp = new Date();

      if(snapshot.exists()) {
        snapshot.forEach(function(childSnapshot) {
          var book = childSnapshot.val();
          let dataBook = new Date(book.orario);
          if(id==book.id && email==book.email && book.status==PENDING && dataBook>=timestamp){
        

            key = childSnapshot.key;
          }          
        });
      }
      return key;    
    }
 
    async function getBookList(email){ //per mostrare tutte le prenotazioni
      var snapshot = await bookRef.once('value');
      let key = [];
      
      let timestamp = new Date();
      
      if(snapshot.exists()) {
         snapshot.forEach(function(childSnapshot) {
            var book = childSnapshot.val();
            let dataBook = new Date(book.orario);
            console.log("TIMESTAMP: "+ timestamp);
            console.log("Data: "+ book.orario);
                if(email == book.email && book.status == PENDING && dataBook>=timestamp){
                  key.push(childSnapshot.key);
                }          
          });
       }      
    return key; 
    }
  
    async function mostraPrenotazioni(agent){
      const email = agent.parameters.email;
    
      const keyList = await getBookList(email);
      console.log("key list: "+keyList);
      console.log("key len:  "+keyList.length);

      if(keyList.length > 0){
        var i;
        for (i = 0; i < keyList.length; i++) {
           var child = await bookRef.child('/'+keyList[i]).once("value");
           console.log(child.val());

               
           await printPrenotazione(agent, child.val());
        }
        welcome(agent);
      }
      else{
         getUndo(agent,`❌ Non esiste alcuna prenotazione con questa email.`);
      }  
    }
  
    async function cancellazione(agent){
       const id = agent.parameters.id;
       const email = agent.parameters.email;


       var key = await getKeyById(id);

     if(key != -1){ //nessuna prenotazione trovata

           console.log("Prenotazione: "+key);

           agent.add("📲 Numero prenotazione: "+id);


           var child = await bookRef.child('/'+key).once("value");
           var val = child.val();

           //console.log("VAL: "+ val);
           if(val.email.toLowerCase() == email.toLowerCase()){

             printPrenotazione(agent, val);           


             await deleteBook(key);
    
             getUndo(agent,"❌ Prenotazione eliminata. ");
             
             const obj = 'Prenotazione cancellata';
             const body = 'La tua prenotazione numero '+ id +' è stata cancellata!';
             
             await sendEmail(obj,body,val.email);


           }else{
             getUndo(agent,"⛔️ Impossibile eliminare la prenotazione di altra gente!");

           }
       }else{
          getUndo(agent,`❌ Non esiste alcuna prenotazione.`);
       }
        
    }

    async function modifica(agent){
        const email = agent.parameters.email;
        const id = agent.parameters.id;
    
        var key = await getKeybyEmail(email,id);
        
        if(key != -1){ //nessuna prenotazione trovata
           var child = await bookRef.child('/'+key).once("value");
           var val = child.val();
           printPrenotazione(agent, val);
          
           const contextData = {'name':'booking','lifespan': 5,'parameters':{'key':key}};
           agent.setContext(contextData);
           console.log("CONTEXT SETTED");
          
           let pl = new Payload(agent.TELEGRAM, card_modifica, { sendAsMessage: true, rawPayload: true });
           agent.add(pl);
    
 
        }else{
          getUndo(agent,`❌ Non esiste alcuna prenotazione futura.`);

        }
       

        
        
      
    //var key = '1234';


    }
  
    async function modificaPosto(agent){
      
        const n_posti = agent.parameters.n_posti; //nuovo numero posti
        var contextIn = agent.getContext('booking');
        const key = contextIn.parameters.key;
      
        console.log("KEY CONTEXT: "+key);
        
        var child = await bookRef.child('/'+key).once("value");
        var val = child.val();
      await setNPosti(key, n_posti, val);
      
    }

    async function modificaOrario(agent){
        const orario = agent.parameters.orario;
        const giorno = agent.parameters.giorno;
      
        var contextIn = agent.getContext('booking');

        const key = contextIn.parameters.key;
        
        console.log("KEY CONTEXT: "+key);

      await setDate(key,giorno,orario);
    
    }
    
    async function modificaOrarioPosto(agent){
        const n_posti = agent.parameters.n_posti; //nuovo numero posti
        var contextIn = agent.getContext('booking');
        const key = contextIn.parameters.key;
        
        var child = await bookRef.child('/'+key).once("value");
        var val = child.val();

        //modifica data
        const orario = agent.parameters.orario;
        const giorno = agent.parameters.giorno;

    
        
        console.log("KEY CONTEXT: "+key);   
      await setDateNPosti(key,val,n_posti,giorno,orario);

    }
  
    async function orariRistorante(agent){
       const open = await getOpeningTime();
       const close = await getClosingTime();
       var text = "🕓\nLe prenotazioni sono accettate dalle: \n<b>" 
       + open + " alle " + close +"</b>";
      
      
       getUndo(agent,text);
    }

     async function setDateNPosti(key,val,newPosti,giorno,orario){
        let d1 = new Date(giorno); //per salvataggio DB
        let d2 = new Date(orario);

        d1.setHours(d2.getHours()+2);
        d1.setMinutes(d2.getMinutes());

        let d3 = new Date(d1); //per controllo
        d3.setHours(d3.getHours()-2);

        let p = await read(orario);
        var child = await bookRef.child('/'+key).once("value");
        var n_posti = val.n_posti;
        var id = val.id;
         let p2 = await read(d3);
        var difN_posti = newPosti-n_posti;

        if(p>-1){
          if(difN_posti <= p2){

            let data_string = formatDate(d1);

            var childRef = bookRef.child("/"+key);
            childRef.update({
                "orario": data_string,"n_posti":newPosti
            });

            let data_p = getDataString(data_string) + " alle " + getTime(data_string); 
            agent.add(`✅\nModifica effettuata.\nData aggiornata al ` + data_p + ` , Numero Posti: `+ newPosti + `.`);
            getUndo(agent,`📲 Conserva il tuo numero di prenotazione:  ` + id);
      
            const obj = "Prenotazione n: "+ val.id + " modificata";
            const body = "<p> Salve, la tua prenotazione n: " + val.id + " è stata modificata. <\p>"
                  +"<p> La data è stata modificata al giorno: "+ getDataString(data_string) + " alle ore: " +  getTime(data_string) + "<\p>"
                  +"<p> Nuovo numero posti: " + newPosti + " <\p>";
            
            await sendEmail(obj,body,val.email);

          }else{
             getUndo(agent,`❌ Posti non disponibili, in questa fascia oraria sono disponibili `+ p + 'posti.');
          }
      }else{
          getUndo(agent,'❌ In questa fascia orario il ristorante è chiuso.');
       }
     }
  
    async function sendEmail(subject,html,email){
      var mailOptions = {
                from: 'mr.alfred.bot@gmail.com',
                to: email,
                subject:subject,
                html:html
          };
          transporter.sendMail(mailOptions, function (err, info) {
            if(err){
              console.log(err);
            }else{
              console.log('Email sent: ' + info.response);
            }

          });
  }
  
    async function setDate(key,giorno,orario){

      let d1 = new Date(giorno); //per salvataggio DB
      let d2 = new Date(orario);

      d1.setHours(d2.getHours()+2);
      d1.setMinutes(d2.getMinutes());

      let d3 = new Date(d1); //per controllo
      d3.setHours(d3.getHours()-2);

      let p = await read(d3);
      var child = await bookRef.child('/'+key).once("value");
      var val = child.val();
      var n_posti = val.n_posti;
      var id = val.id;
      if(p>-1){
          if(n_posti <= p){

            let data_string = formatDate(d1);

            var childRef = bookRef.child("/"+key);
            childRef.update({
                "orario": data_string,
            });
            let data_p = getDataString(data_string) + " alle " + getTime(data_string); 
            agent.add(`✅\nModifica effettuata.\nData aggiornata al ` + data_p);
            getUndo(agent,`📲 Conserva il tuo numero di prenotazione:  ` + id);
      
            const obj = "Prenotazione n: "+ val.id + " modificata";
            const body = "<p> Salve, la tua prenotazione n: " + val.id + " è stata modificata. <\p>"
                  +"<p> La data è stata modificata al giorno: "+ getDataString(data_string) + " alle ore: " +  getTime(data_string) + "<\p>"
                  +"<p> Numero posti: " + val.n_posti + " <\p>";
            
            await sendEmail(obj,body,val.email);

          }else{
             getUndo(agent,`❌ Posti non disponibili, in questa fascia oraria sono disponibili `+ p + 'posti.');
          }
      }else{
          getUndo(agent,'❌ In questa fascia orario il ristorante è chiuso.');
       }
    }
  
    async function setNPosti(key,newPosti, val){

        var data = val.orario;
        var n_posti = val.n_posti;
      var id = val.id;

        
        let p = await read(data);
        var difN_posti = newPosti-n_posti;

        if(difN_posti <= p){
          var childRef = bookRef.child("/"+key);
          childRef.update({
              "n_posti":newPosti
          });

          let data_p = getDataString(data) + " alle " + getTime(data); 
          agent.add(`✅\nModifica effettuata.\nNuovo numero posti ` + newPosti + `.`);
          getUndo(agent,`📲 Conserva il tuo numero di prenotazione:  ` + id);
          
          const obj = "Prenotazione n: "+ val.id + " modificata";
          const body = "<p> Salve, la tua prenotazione n:" + val.id + " è stata modificata. <\p>"
                    +"<p> Il nuovo numero posti è: " + newPosti + "<\p>"
                    +"<p> Giorno "+ getDataString(val.orario) + " alle ore " +  getTime(val.orario) + "<\p>";
            
          await sendEmail(obj,body,val.email);

        }else{
          getUndo(agent,`❌ Posti non disponibili,\nin questa fascia oraria sono disponibili `+ p + 'posti.')
        }

      }
  
  
   	async function noteOk(agent){
      const nota = agent.parameters.note;
      
      var contextIn = agent.getContext('insert');
      const key = contextIn.parameters.key;
          
      console.log("CT: "+ key);
      
      var childRef = bookRef.child("/"+key);
          childRef.update({
              "nota": nota,
          });
      
      getUndo(agent,"📲 Nota al ristoratore aggiunta. ");

    }
  
  	async function noteNo(agent){
      getUndo(agent,"Nessun problema. ");
    }
    
    // Run the proper function handler based on the matched Dialogflow intent name
    let intentMap = new Map();
    intentMap.set('Default Welcome Intent', welcome);
    intentMap.set('Default Fallback Intent', fallback);
    intentMap.set('prenotazione', prenota);
    intentMap.set('disponibilita', disponibilita);
    intentMap.set('cancellazione', cancellazione);
    intentMap.set('mostraPrenotazioni', mostraPrenotazioni);
    intentMap.set('modifica', modifica);
    intentMap.set('modificaPosto', modificaPosto);
    intentMap.set('modificaOrario', modificaOrario);
    intentMap.set('modificaOrarioPosto', modificaOrarioPosto);
    intentMap.set('orariRistorante',orariRistorante);    
   
    
  	intentMap.set('prenotazione-yes',noteOk);
   	intentMap.set('prenotazione-no',noteNo);  

    intentMap.set('test', test);
  
    // intentMap.set('your intent name here', googleAssistantHandler);
    agent.handleRequest(intentMap);
});
