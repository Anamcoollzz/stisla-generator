<template>
    <form>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Path Projek</label>
                    <input type="text" class="form-control" v-model="data.path">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nama Modul</label>
                    <input type="text" class="form-control" v-model="data.modul">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nama Controller</label>
                    <input type="text" class="form-control" v-model="data.controller">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nama Model</label>
                    <input type="text" class="form-control" v-model="data.model">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nama Tabel</label>
                    <input type="text" class="form-control" v-model="data.tabel">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nama Migration</label>
                    <input type="text" class="form-control" v-model="data.migration">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nama Seeder</label>
                    <input type="text" class="form-control" v-model="data.seeder">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Folder Views</label>
                    <input type="text" class="form-control" v-model="data.views">
                </div>
            </div>
        </div>

        <h3 align="center">Kolom pada tabel</h3>
        <template v-for="(kol, i) in data.kolom">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="">Kolom {{ i+1 }}</label>
                        <input type="text" class="form-control" v-model="data.kolom[i].nama">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="">Tipe {{ i+1 }}</label>
                        <select v-model="data.kolom[i].tipe" class="form-control">
                            <option value="bigIncrements">bigIncrements [Primary]</option>
                            <option value="increments">increments [Primary]</option>
                            <option value="tinyIncrements">tinyIncrements [Primary]</option>
                            <option value="bigInteger">bigInteger</option>
                            <option value="integer">integer</option>
                            <option value="tinyInteger">tinyInteger</option>
                            <option value="string">string</option>
                            <option value="double">double</option>
                            <option value="date">date</option>
                            <option value="year">year</option>
                            <option value="timestamp">timestamp</option>
                            <option value="rememberToken">rememberToken</option>
                            <option value="uuid">uuid</option>
                        </select>
                    </div>
                </div>
                <template v-if="bukanPrimary(data.kolom[i].tipe)">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="">Panjang {{ i+1 }}</label>
                            <input type="number" min="0" class="form-control" v-model="data.kolom[i].panjang">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input @change="isUnique(data.kolom[i])" class="mt-5" type="checkbox" v-model="data.kolom[i].unique"> Unique
                    </div>
                    <div class="col-md-12">
                        <h4 align="center">Validasi kolom {{ i+1 }}</h4>
                        <input type="checkbox" v-model="data.kolom[i].rules" value="required"> required
                        <input type="checkbox" v-model="data.kolom[i].rules" value="email"> email
                        <input type="checkbox" v-model="data.kolom[i].rules" value="string"> string
                        <input type="checkbox" v-model="data.kolom[i].rules" value="numeric"> numeric
                        <input type="checkbox" v-model="data.kolom[i].rules" value="file"> file
                        <input type="checkbox" v-model="data.kolom[i].rules" value="mimes:jpeg,png"> image
                        <br>
                        <input type="checkbox" v-model="data.kolom[i].rules" :value="'unique:'+unique"> unique
                        <input type="text" v-model="unique">
                        <br>
                        <input type="checkbox" v-model="data.kolom[i].rules" :value="'exists:'+exists"> exists
                        <input type="text" v-model="exists">
                        <br>
                        <input type="checkbox" v-model="data.kolom[i].rules" :value="'min:'+min"> min
                        <input type="number" min="0" v-model="min">
                        <br>
                        <input type="checkbox" v-model="data.kolom[i].rules" :value="'max:'+max"> max
                        <input type="number" min="0" v-model="max">
                        <hr>
                    </div>
                </template>
            </div>
        </template>
        <button @click.prevent="data.kolom.push({nama:'',tipe:'string',panjang:null,unique:false,rules:[]})" class="btn btn-success btn-block mb-4">Tambah Kolom</button>
        <center>
            <input type="checkbox"> CRUD
            <input type="checkbox" v-model="data.timestamps"> Timestamps
        </center>
        <button @click.prevent="generate" class="btn btn-primary btn-block">Generate</button>
    </form>
</template>

<script>
    export default {
        data(){
            return {
                data : {
                    path : 'C:\\xampp\\htdocs\\ujicoba',
                    modul : '',
                    controller : '',
                    model : '',
                    migration : '',
                    seeder : '',
                    views : '',
                    kolom : [{
                        nama : 'id',
                        tipe : 'bigIncrements',
                        panjang : null,
                        unique : false,
                        rules : [],
                    }],
                    timestamps : true,
                    tabel : '',
                },
                min : 0,
                max : 0,
                unique : 'tabel,kolom',
                exists : 'tabel,kolom',
            }
        },
        mounted() {
            this.data.modul = this.modul.nama
            this.data.tabel = this.camelToSnake(this.data.modul)
            this.unique = this.data.tabel+',kolom'
            this.exists = this.data.tabel+',kolom'
            this.data.path = this.modul.projek.path
            $('#rules').select2({
                multiple : true,
            })
            $('#rules').on('change.select', (e)=>{
                console.log(e)
            })
            setTimeout(()=>{
                if(this.modul.controller)
                    this.data.controller = this.modul.controller
                if(this.modul.model)
                    this.data.model = this.modul.model
                if(this.modul.tabel)
                    this.data.tabel = this.modul.tabel
                if(this.modul.migration)
                    this.data.migration = this.modul.migration
                if(this.modul.seeder)
                    this.data.seeder = this.modul.seeder
                if(this.modul.views)
                    this.data.views = this.modul.views
                if(this.modul.kolom.length > 0)
                    this.data.kolom = this.modul.kolom
                console.log(this.data.kolom)
            }, 1000)
        },
        watch : {
            'data.modul'(newValue){
                if(newValue.trim() == ''){
                    this.data.controller = ''
                    this.data.model = ''
                    this.data.tabel = ''
                    this.data.migration = ''
                    this.data.seeder = ''
                    this.data.views = ''
                }else{
                    let date = new Date()
                    let tahun = date.getFullYear()
                    let bulan = date.getMonth() + 1
                    let tanggal = date.getDate()
                    let jam = date.getHours()
                    let menit = date.getMinutes()
                    let detik = date.getSeconds()
                    newValue = this.titleCase(newValue)
                    let modulTanpaSpasi = newValue.replace(/ /g,'')
                    this.data.controller = modulTanpaSpasi+'Controller'
                    this.data.model = modulTanpaSpasi
                    this.data.migration = tahun+'_'+bulan+'_'+tanggal+'_'+jam+menit+detik+'_buat_tabel_'+this.camelToSnake(modulTanpaSpasi)
                    this.data.seeder = modulTanpaSpasi+'Seeder'
                    this.data.views = this.camelToSnake(modulTanpaSpasi)
                    this.data.tabel = this.data.views
                }
            },
            'data.tabel'(newValue){
                this.unique = newValue+',kolom'
                this.exists = newValue+',kolom'
                console.log(this.unique)
            }
        },
        methods : {
            camelToSnake(string) {
                return string.replace(/[\w]([A-Z])/g, function(m) {
                    return m[0] + "_" + m[1];
                }).toLowerCase()
            },
            titleCase(str) {
                var splitStr = str.toLowerCase().split(' ');
                for (var i = 0; i < splitStr.length; i++) {
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
                }
                return splitStr.join(' ');
            },
            generate(){
                let data = this.data;
                if(data.modul.trim() == ''){
                    alert('Masukkan data dengan benar')
                }
                axios.post('/generate/'+this.modul.id, data).then(function(response){

                }).catch(error=>{
                    console.log(error)
                })
            },
            rulesChange(e, rules){
                console.log(e)
                console.log(rules)
            },
            bukanPrimary(tipe){
                return ![
                'bigIncrements',
                'increments',
                'tinyIncrements',
                ].includes(tipe)
            },
            isUnique(kolom){
                if(kolom.unique){
                    kolom.rules.push('unique:'+this.unique)
                }else{
                    kolom.rules.remove('unique:'+this.unique)
                }
            }
        },
        props : {
            modul : {
                type : Object,
                required : true,
            }
        }
    }
</script>
