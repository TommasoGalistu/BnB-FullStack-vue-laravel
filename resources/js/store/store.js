import { reactive } from 'vue';


const store = reactive({
    // data of user
    user: {},
    userName: 'Accedi',
    // is logged user boolean
    is_logged: false,
    have_apartment: false,

    // boolean for dropdown menu of header
    is_open: false,
    // all apartment visible
    allApartments: [],
    // apartment of singol user
    userApartment: [],

    center: [],
    // filtred apartment near of the center of map
    filtredApartment: [],

    minLat: '',
    maxLat: '',
    minLong: '',
    maxLong: '',

    filtredApartment: [],
})

const checkAdress = (adress) =>{
// trasform adress in array
let stringRequest = adress
// .split(" ")
// .map((world, index, arr) => {
//     if(arr.length - 1 !== index){
//         return world + "%20";
//     }else{
//         return world;
//     }

// })
// .join("").toUpperCase();
let urlRequest = `https://api.tomtom.com/search/2/geocode/${encodeURIComponent(stringRequest)}.json?key=qNjsW3gGJOBNhFoXhBzsGRJAk5RJMJhI`;

return urlRequest
//   array
}

// find a square point zone in the center of map
const findZone = (lon, lat, distance = 20) =>{
     // Il raggio in chilometri, in questo caso 20 km
     const earthRadiusKm = 6371; // Raggio della Terra in km

     // Delta della latitudine (1 grado di latitudine ≈ 111.32 km)
     const deltaLat = distance / 111.32;

     // Delta della longitudine (corretto per la latitudine)
     const deltaLong = distance / (111.32 * Math.cos(lat * Math.PI / 180));

     // Limiti dell'area rettangolare
     store.minLat = lat - deltaLat;
     store.maxLat = lat + deltaLat;
     store.minLong = lon - deltaLong;
     store.maxLong = lon + deltaLong;

}

const filterApartment = (apartments) => {


    // apartment filtred near the center of map
    store.filtredApartment = apartments.filter(apartment =>{
        return apartment.lon >= store.minLong && apartment.lon <= store.maxLong && apartment.lat >= store.minLat &&  apartment.lat <= store.maxLat
    })





}


export {store, checkAdress, findZone, filterApartment};
