<?php
session_start();

//membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","data barang");

// Menambah Barang Baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    // Query untuk menambahkan data ke tabel 'stock'
    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES ('$namabarang', '$deskripsi', '$stock')");

    // Cek apakah query berhasil
    if($addtotable){
        header('Location: index.php');
    } else {
        echo 'Gagal menambahkan barang baru';
        header('Location: index.php');
    }
}



//Menambah Barang Masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang,keterangan,qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang= '$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    }else {
        echo 'gagal';
        header('location:masuk.php');
      }
}


//Menambah Barang Keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if($stocksekarang >= $qty){
        //kalau barangnya cukup
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang,penerima,qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang= '$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:keluar.php');
    }else {
        echo 'gagal';
        header('location:keluar.php');
      }
    }else{
        //kalau barangnya gak cukup
        echo '
        <script>
            alert("stock saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>
        ';
    }

}



//update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
    if($update){
        header('location:index.php');
    }else {
        echo 'gagal';
        header('location:index.php');
    }
}


//Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($update){
        header('location:index.php');
    }else {
        echo 'gagal';
        header('location:index.php');
    }
};



//Mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "update stock set stock ='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn,"update masuk set qty= '$qty',keterangan ='$deskripsi' where idmasuk='$idm'");
            if($kurangistocknya&&$updatenya){
                header('location:masuk.php');
            }else {
                echo 'gagal';
                header('location:masuk.php');
            }
    }else{
            $selisih = $qtyskrg-$qty;
            $kurangin = $stockskrg - $selisih;
            $kurangistocknya = mysqli_query($conn, "update stock set stock ='$kurangin' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"update masuk set qty= '$qty',keterangan ='$deskripsi' where idmasuk='$idm'");
                if($kurangistocknya&&$updatenya){
                    header('location:masuk.php');
                }else {
                    echo 'gagal';
                    header('location:masuk.php');
                } 
            }
}




//Menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock-$qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");


    if($update&&$hapusdata){
        header('location:masuk.php');
    }else{
        header('location:masuk.php');
    }

}

//Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty']; //Qty baru inputan user

    //mengambil stock barang saat ini 
    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];


    //qty barang keluar saat ini 
    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih;

        if($selisih <= $stockskrg){
            //stock cukup, keluarin stock
            //update table keluar, stock
            $kurangistocknya = mysqli_query($conn, "update stock set stock ='$kurangin' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"update keluar set qty= '$qty',penerima ='$penerima' where idkeluar='$idk'");
                if($kurangistocknya&&$updatenya){
                    header('location:keluar.php');
                }else {
                    echo 'gagal';
                    header('location:keluar.php');
                }
        }else{
           echo '
           <script>alert("Stock tidak mencukupi");
           window.location.href="keluar.php" 
           </script>
           ';
        }



       
    }else{
            $selisih = $qtyskrg-$qty;
            $kurangin = $stockskrg + $selisih;
            $kurangistocknya = mysqli_query($conn, "update stock set stock ='$kurangin' where idbarang='$idb'");
            $updatenya = mysqli_query($conn,"update keluar set qty= '$qty',penerima ='$penerima' where idkeluar='$idk'");
                if($kurangistocknya&&$updatenya){
                    header('location:keluar.php');
                }else {
                    echo 'gagal';
                    header('location:keluar.php');
                } 
            }


}
//Menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock+$qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");


    if($update&&$hapusdata){
        header('location:keluar.php');
    }else{
        header('location:keluar.php');
    }

}


//menambah admin baru
if(isset($_POST['addadmin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn,"insert into login (email, password) values('$email','$password')");

    if($queryinsert){
        //if berhasil
        header('location:admin.php');

    }else{
        //kalau gagal insert ke db
        header('location:admin.php');
    }
}


//edit data admin
if(isset($_POST['updateadmin'])){
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn,"update login set email='$emailbaru', password='$passwordbaru' where iduser='$idnya'");

    if($queryupdate){
        header('location:admin.php');

    }else{
        header('location:admin.php');


    }
}


//hapus admin
if(isset($_POST['hapusrequest'])){
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn,"delete from login where iduser='$id'");

    if($querydelete){
        header('location:request.php');

    }else{
        header('location:request.php');


    }

}

//meminjam barang
if(isset($_POST['pinjam'])){
    $idbarang = $_POST['barangnya']; //mengambil id barang
    $qty = $_POST['qty']; //mengambil jumlah quantity
    $penerima = $_POST['penerima']; //mengambil nama penerima

    //ambil stock sekarang
    $stock_saat_ini = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
    $stock_nya = mysqli_fetch_array($stock_saat_ini);
    $stock = $stock_nya['stock']; //imi value nya

    //kurangi stocknya
    $new_stock = $stock-$qty;

    //mulai query insert
    $insertpinjam = mysqli_query($conn,"INSERT INTO peminjaman (idbarang,qty,peminjam) values('$idbarang','$qty','$penerima')");


    //mengurangi stock di table stock
    $kurangistock = mysqli_query($conn,"update stock set stock='$new_stock' where idbarang='$idbarang'");

    
    if($insertpinjam&&$kurangistock){
        //jika berhasil
        echo '
        <script>
                alert("Berhasil");
                window.location.href="peminjaman.php";
                </script>
                ';
    }else{
        //jika gagal
        echo '
        <script>
                alert("Gagal");
                window.location.href="peminjaman.php";
                </script>
                ';
    }

}


//menyelesaikan pinjaman
if(isset($_POST['barangkembali'])){
    $idpinjam = $_POST['idpinjam'];
    $idbarang = $_POST['idbarang'];
    //eksekusi
    $update_status = mysqli_query($conn,"update peminjaman set status='kembali' where idpeminjaman='$idpinjam'");

     //ambil stock sekarang
     $stock_saat_ini = mysqli_query($conn,"select * from stock where idbarang='$idbarang'");
     $stock_nya = mysqli_fetch_array($stock_saat_ini);
     $stock = $stock_nya['stock']; //imi value nya

     //ambil qty dari  si idpinjam sekarang
     $stock_saat_ini1 = mysqli_query($conn,"select * from peminjaman where idpeminjaman='$idpinjam'");
     $stock_nya1 = mysqli_fetch_array($stock_saat_ini1);
     $stock1 = $stock_nya1['qty']; //imi value nya
 
 
     //kurangi stocknya
     $new_stock = $stock1+$stock;

    //kembalikan stocknya
    $kembalikan_stock = mysqli_query($conn,"update stock set stock ='$new_stock' where idbarang='$idbarang'");


    if($update_status&&$kembalikan_stock){
        //jika berhasil
        echo '
        <script>
                alert("Berhasil");
                window.location.href="peminjaman.php";
                </script>
                ';
    }else{
        //jika gagal
        echo '
        <script>
                alert("Gagal");
                window.location.href="peminjaman.php";
                </script>
                ';
    }

}
?>

