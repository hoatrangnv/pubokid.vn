$.verify.updateRules({
	email: {
		message: "Địa chỉ email không hợp lệ."
	},
	required: {
		messages: {
			"all": "Chỗ này phải có thông tin."
		}
	},
	alphanumeric: {
		message: "Hãy nhập mật khẩu của bạn bằng ký tự chữ hoặc số."
	},
	size: {
		messages: {
			exact: "Phải có #exact# ký tự",
			between: "Phải có từ #lower# đến #upper# ký tự"
		}
	},
	number: {
		message: "Vui lòng chọn chữ số"
	}
    phone: {
		message: "Vui lòng điền đúng số điện thoại của bạn"
	}
    agreement: {
		message: "Chưa check vào đồng ý"
	}
});

