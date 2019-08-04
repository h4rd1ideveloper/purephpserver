import Swal from 'sweetalert2';
export const FeedBack = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});