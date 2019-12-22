<template>
    <form>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Path Projek</label>
                    <input type="text" class="form-control" v-model="data.path">
                </div>
            </div>
            <template v-for="(m, index) in data.menu">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" v-model="m.nama">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Ikon</label>
                        <input type="text" class="form-control" v-model="m.ikon">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Route</label>
                        <input type="text" class="form-control" v-model="m.route">
                    </div>
                </div>
                <div class="col-md-2">
                    <input class="mt-5" type="checkbox" v-model="m.is_blank"> Is Blank
                </div>
                <div class="col-md-1">
                    <button class="btn btn-danger mt-4 btn-sm" @click.prevent="data.menu.splice(index, 1)">x</button>
                    <button class="btn btn-primary mt-4 btn-sm" @click.prevent="tambahSub(m)">Tambah Sub</button>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-success" @click.prevent="sisipkanMenu(index)">Sisipkan Menu</button>
                </div>
                <div class="col-md-12" v-if="m.sub.length > 0">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sub Menu</h3>
                        </div>
                        <div class="card-body" style="background-color: #969FB2;">
                            <template v-for="(sub, a) in m.sub">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Nama</label>
                                            <input type="text" class="form-control" v-model="sub.nama">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Ikon</label>
                                            <input type="text" class="form-control" v-model="sub.ikon">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Route</label>
                                            <input type="text" class="form-control" v-model="sub.route">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input class="mt-5" type="checkbox" v-model="sub.is_blank"> Is Blank
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-danger mt-4 btn-sm" @click.prevent="m.sub.splice(a, 1)">x</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <button @click.prevent="tambahMenu" class="btn btn-success btn-block mb-4">Tambah Menu</button>
        <button @click.prevent="generate" class="btn btn-primary btn-block">Generate</button>
        <h4 class="mt-3">Jalankan perintah berikut</h4>
        <textarea class="form-control">php artisan db:seed --class=GenerateMenu --force</textarea>
    </form>
</template>

<script>
    export default {
        data(){
            return {
                data : {
                    menu : [],
                    path : 'C:\\xampp\\htdocs\\ujicoba',
                },
            }
        },
        mounted() {
            this.data.path = this.projek.path
            this.data.menu = this.menu
            if(this.data.menu.length <= 0){
                this.tambahMenu()
            }
        },
        watch : {
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
                if(data.menu.length <= 0){
                    alert('Masukkan data dengan benar')
                }
                axios.post('/generate-menu/'+this.projek.id, data).then(function(response){

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
            },
            perbaruiUnik(kolomm, unik = true){
                let indexArr = [];
                let teks = 'unique'
                if(!unik)
                    teks = 'exists'
                kolom.rules.forEach((item, index)=>{
                    if(item.includes(teks)){
                        indexArr.push(index)
                    }
                })
                indexArr.forEach((item, index)=>{
                    kolom.rules.splice(item, 1);
                })
            },
            setNama(kolom){
                let nama_asli = this.titleCase(kolom.nama_asli.replace(/ /g,'_'))
                kolom.nama = this.camelToSnake(nama_asli)
                kolom.tabel_kolom = this.data.tabel+','+kolom.nama
            },
            tambahMenu(){
                this.data.menu.push({
                    nama : '',
                    ikon : '',
                    route : '',
                    is_blank : null,
                    sub : [],
                })
            },
            tambahSub(menu){
                menu.sub.push({
                    nama : '',
                    ikon : '',
                    route : '',
                    is_blank : ''
                })
            },
            sisipkanMenu(index){
                this.data.menu.splice(index+1, 0, {
                    nama : '',
                    ikon : '',
                    route : '',
                    is_blank : null,
                    sub : [],
                })
            }
        },
        props : {
            menu : {
                type : Array,
                required : true,
            },
            projek : {
                type : Object,
                required : true,
            },
        }
    }
</script>
