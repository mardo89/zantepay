window.onload = function() {

    if (typeof web3 !== 'undefined') {
        web3 = new Web3(web3.currentProvider);
    }
    else {
        $("#metamask_missing").html('You need <a target="_blank" href="https://metamask.io/">MetaMask</a> browser plugin to run this');
        web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
    }
}
var CrowdSaleContract = web3.eth.contract([
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "_from",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_to",
				"type": "address"
			}
		],
		"name": "OwnershipTransferred",
		"type": "event"
	},
	{
		"constant": false,
		"inputs": [],
		"name": "acceptOwnership",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "userId",
				"type": "bytes32"
			}
		],
		"name": "createUserProxy",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "newWallet",
				"type": "address"
			}
		],
		"name": "setWallet",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_newOwner",
				"type": "address"
			}
		],
		"name": "transferOwnership",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": false,
				"name": "from",
				"type": "address"
			},
			{
				"indexed": false,
				"name": "amount",
				"type": "uint256"
			},
			{
				"indexed": false,
				"name": "data",
				"type": "bytes"
			}
		],
		"name": "UserDeposited",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "_from",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_to",
				"type": "address"
			}
		],
		"name": "OwnershipTransferProposed",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "userId",
				"type": "bytes32"
			},
			{
				"indexed": false,
				"name": "proxy",
				"type": "address"
			}
		],
		"name": "UserProxyCreated",
		"type": "event"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "amount",
				"type": "uint256"
			}
		],
		"name": "withdrawFunds",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"payable": true,
		"stateMutability": "payable",
		"type": "fallback"
	},
	{
		"inputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "constructor"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "newOwner",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "owner",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "wallet",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	}
]);
var ContractAddress = "0xe9ebe067ed20550eabf02eab6edda92e530ecfb6";
var crowdSale = CrowdSaleContract.at(ContractAddress);
// Current owner address

crowdSale.owner( function (err, res) {
    if (err) {
        return;
    }
    $("#current_crowdsale_owner").text(res);
});
// Transfer ownership
$("#set_new_crowdsale_owner").click(function() {
    crowdSale.transferOwnership($("#new_crowdsale_owner_address").val(), (err, res) => {
        if (err) {
            return;
        }
    });
});

// New owner pending address
crowdSale.newOwner( function (err, res) {
    if (err) {
        return;
    }
    $("#pending_crowdsale_owner_address").text(res);
});

// Accept ownership
$("#accept_crowdsale_ownership").click(function() {
    crowdSale.acceptOwnership((err, res) => {
            if (err) {
                return;
            }
    });
});

// Set wallet
$("#set_crowdsale_wallet").click(function() {
    crowdSale.setWallet($("#new_crowdsale_wallet_address").val(), (err, res) => {
        if (err) {
            return;
        }
    });
});

// Current Wallet address
crowdSale.wallet( function (err, res) {
    if (err) {
        return;
    }
    $("#current_crowdsale_wallet").text(res);
});


// Contract address current balance
web3.eth.getBalance(ContractAddress, function (err, res) {
    if (err) {
        return;
    }
    var value = web3.fromWei(res, 'Wei');
    $("#crowdsale_address_balance").text(value);
});

// Withdraw funds wallet
$("#withdraw_crowdsale_funds").click(function() {
	var withdrawAmount = web3.toBigNumber($("#crowdsale_withdrawAmount").val());
    crowdSale.withdrawFunds(withdrawAmount, (err, res) => {
    if (err) {
        return;
    }
    });
});
