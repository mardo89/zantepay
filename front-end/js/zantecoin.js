window.onload = function() {
    if (typeof web3 !== 'undefined') {
        web3 = new Web3(web3.currentProvider);
    }
    else {
        $("#metamask_missing").html('You need <a target="_blank" href="https://metamask.io/">MetaMask</a> browser plugin to run this');
        web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
    }
}
var ZanteCoinContract = web3.eth.contract([
    {
        "constant": true,
        "inputs": [],
        "name": "MAX_CONTRIBUTION",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "decimals",
        "outputs": [
            {
                "name": "",
                "type": "uint8"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "coinsIssuedTotal",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "coinsIssuedMkt",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_ICO_PHASE_2",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "coinsIssuedCmp",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [
            {
                "name": "",
                "type": "address"
            }
        ],
        "name": "balances",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [
            {
                "name": "_owner",
                "type": "address"
            }
        ],
        "name": "balanceOf",
        "outputs": [
            {
                "name": "balance",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_ICO_PHASE_3",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [
            {
                "name": "",
                "type": "address"
            },
            {
                "name": "",
                "type": "address"
            }
        ],
        "name": "allowed",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_ICO_PHASE_1",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [
            {
                "name": "_owner",
                "type": "address"
            },
            {
                "name": "_spender",
                "type": "address"
            }
        ],
        "name": "allowance",
        "outputs": [
            {
                "name": "remaining",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_ICO_PHASE_0",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_ICO_TOTAL",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "name",
        "outputs": [
            {
                "name": "",
                "type": "string"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_MKT_TOTAL",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
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
        "name": "COIN_SUPPLY_TOTAL",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
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
        "name": "DATE_ICO_END",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "symbol",
        "outputs": [
            {
                "name": "",
                "type": "string"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "DATE_ICO_START",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "totalSupply",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "MIN_CONTRIBUTION",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "COIN_SUPPLY_COMPANY_TOTAL",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
    },
    {
        "constant": true,
        "inputs": [],
        "name": "coinsIssuedIco",
        "outputs": [
            {
                "name": "",
                "type": "uint256"
            }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
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
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "fallback"
    },
    {
        "constant": false,
        "inputs": [
            {
                "name": "_spender",
                "type": "address"
            },
            {
                "name": "_amount",
                "type": "uint256"
            }
        ],
        "name": "approve",
        "outputs": [
            {
                "name": "success",
                "type": "bool"
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
        "constant": false,
        "inputs": [
            {
                "name": "_participant",
                "type": "address"
            },
            {
                "name": "_coins",
                "type": "uint256"
            }
        ],
        "name": "grantCompanyCoins",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "name": "_owner",
                "type": "address"
            },
            {
                "indexed": false,
                "name": "_coins",
                "type": "uint256"
            }
        ],
        "name": "IcoCoinsIssued",
        "type": "event"
    },
    {
        "constant": false,
        "inputs": [
            {
                "name": "_participant",
                "type": "address"
            },
            {
                "name": "_coins",
                "type": "uint256"
            }
        ],
        "name": "grantMarketingCoins",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
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
        "inputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "constructor"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "name": "_owner",
                "type": "address"
            },
            {
                "indexed": true,
                "name": "_spender",
                "type": "address"
            },
            {
                "indexed": false,
                "name": "_value",
                "type": "uint256"
            }
        ],
        "name": "Approval",
        "type": "event"
    },
    {
        "constant": false,
        "inputs": [
            {
                "name": "_to",
                "type": "address"
            },
            {
                "name": "_amount",
                "type": "uint256"
            }
        ],
        "name": "transfer",
        "outputs": [
            {
                "name": "success",
                "type": "bool"
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
                "name": "_participant",
                "type": "address"
            },
            {
                "name": "_coins",
                "type": "uint256"
            }
        ],
        "name": "issueIcoCoins",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
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
            },
            {
                "indexed": false,
                "name": "_value",
                "type": "uint256"
            }
        ],
        "name": "Transfer",
        "type": "event"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "name": "_participant",
                "type": "address"
            },
            {
                "indexed": false,
                "name": "_coins",
                "type": "uint256"
            },
            {
                "indexed": false,
                "name": "_balance",
                "type": "uint256"
            }
        ],
        "name": "CompanyCoinsGranted",
        "type": "event"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "name": "_participant",
                "type": "address"
            },
            {
                "indexed": false,
                "name": "_coins",
                "type": "uint256"
            },
            {
                "indexed": false,
                "name": "_balance",
                "type": "uint256"
            }
        ],
        "name": "MarketingCoinsGranted",
        "type": "event"
    },
    {
        "constant": false,
        "inputs": [
            {
                "name": "_from",
                "type": "address"
            },
            {
                "name": "_to",
                "type": "address"
            },
            {
                "name": "_amount",
                "type": "uint256"
            }
        ],
        "name": "transferFrom",
        "outputs": [
            {
                "name": "success",
                "type": "bool"
            }
        ],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
    }
]);
var ContractAddress = "0x4e1ad19920b1b084681b77adbbcbfc4369dd8c8a";
var zanteCoin = ZanteCoinContract.at(ContractAddress);   
           
// Coin symbol
zanteCoin.symbol( function (err, res) {
    if (err) {
        return;
    }
    $("#symbol").text(res);
    });
       
// Coin supply total
zanteCoin.COIN_SUPPLY_TOTAL( function (err, res) {
    if (err) {
        return;
    }
    $("#coin_total_supply").text(res);
});

// ICO coins section
zanteCoin.COIN_SUPPLY_ICO_TOTAL ( function (err, res) {
    if (err) {
        return;
    }
    var ico_total_supply = res;
    $("#total_ico_coins_supply").text(res);
    
    zanteCoin.coinsIssuedIco ( function (err, res) {
        if (err) {
            return;
        }
        var ico_coins_issued = res;
        var ico_coins_availabe = ico_total_supply - ico_coins_issued;
        $("#ico_coins_available").text(ico_coins_availabe);
    });
});

// TODO: this part is demo. Need to connect user wallet addresses database
$("#ico-participants").on('click','#issue_ico',function(){
    // get the current row
    var currentRow=$(this).closest("tr"); 
    var wallet_address = currentRow.find("td:eq(1)").text(); // TODO: get user wallet address
    var tokens_amount = currentRow.find("td:eq(2)").text(); // TODO: get user tokens amount
    // TODO: ICO issue coins need to parameters user wallet address and token balance	
    zanteCoin.issueIcoCoins(wallet_address,tokens_amount, (err, res) => {
        if (err) {	
            return;
        }
        
    });
});
// ICO coins section end

// Grant Company section
zanteCoin.COIN_SUPPLY_COMPANY_TOTAL ( function (err, res) {
    if (err) {
        return;
    }
    var grant_company_supply = res;
    $("#total_grant_company_supply").text(res);

    zanteCoin.coinsIssuedCmp ( function (err, res) {
        if (err) {
            return;
        }
        var grant_company_issued = res;
        var grant_company_available = grant_company_supply - grant_company_issued;
        $("#grant_company_available").text(grant_company_available);
    });
});

// Grant company coins issue
$("#grant_company_coins").click(function() {
    zanteCoin.grantCompanyCoins($("#grant_company_address").val(), $("#grant_company_amount").val(), (err, res) => {
        if (err) {
            return;
        }
    });
});
// Grant Company section end

// Grant Marketing section start
zanteCoin.COIN_SUPPLY_MKT_TOTAL ( function (err, res) {
    if (err) {
        return;
    }
    var grant_marketing_supply = res;
    $("#total_grant_marketing_supply").text(res);
    
    zanteCoin.coinsIssuedMkt ( function (err, res) {
        if (err) {
            return;
        }
        var grant_marketing_issued = res;
        var grant_marketing_available = grant_marketing_supply - grant_marketing_issued;
        $("#grant_marketing_available").text(grant_marketing_available);
    });
});

// Grant marketing coins issue
$("#grant_marketing_coins").click(function() {
    zanteCoin.grantMarketingCoins($("#grant_marketing_address").val(), $("#grant_marketing_amount").val(), (err, res) => {
        if (err) {
            return;
        }
    });
});

// Grant Marketing section end

// Current owner address
zanteCoin.owner( function (err, res) {
    if (err) {
        return;
    }
    $("#current_owner").text(res);
});

// Transfer ownership
$("#set_new_owner").click(function() {
    zanteCoin.transferOwnership($("#new_owner_address").val(), (err, res) => {
    if (err) {
        return;
    }
    });
});

// New owner pending address
zanteCoin.newOwner( function (err, res) {
    if (err) {
        return;
    }
    $("#pending_owner_address").text(res);
});

// Accept ownership
$("#accept_ownership").click(function() {
    zanteCoin.acceptOwnership((err, res) => {
        if (err) {
            return;
        } 
    });
});
