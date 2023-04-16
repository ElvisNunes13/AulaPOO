<?php
class ItemCardapio {
  private $id;
  private $descricao;
  private $preco;
  private $quantidade;
  private $pedido;

  public function __construct($id, $descricao, $preco, $quantidade, $pedido) {
    $this->setId($id);
    $this->setDescricao($descricao);
    $this->setPreco($preco);
    $this->setQuantidade($quantidade);
    $this->setPedido($pedido);
  }

  public function calcularPrecoFinal() {
    return $this->getPreco() * $this->getQuantidade();
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getDescricao() {
    return $this->descricao;
  }

  public function setDescricao($descricao) {
    $this->descricao = $descricao;
  }

  public function getPreco() {
    return $this->preco;
  }

  public function setPreco($preco) {
    if ($preco > 0) {
      $this->preco = $preco;
    } else {
      echo "O preço deve ser maior do que zero";
    }
  }

  public function getQuantidade() {
    return $this->quantidade;
  }

  public function setQuantidade($quantidade) {
    if ($quantidade > 0) {
      $this->quantidade = $quantidade;
    } else {
      echo "A quantidade deve ser maior do que zero";
    }
  }

  public function getPedido() {
    return $this->pedido;
  }

  public function setPedido($pedido) {
    $this->pedido = $pedido;
  }
}

class Pedido {
  private $idPedido;
  private $precoTotal;
  private static $contadorPedidos = 0;
  private $itens = [];

  public function __construct() {
    self::$contadorPedidos++;
    $this->setIdPedido(self::$contadorPedidos);
  }

  public function adicionarItem(ItemCardapio $item) {
    $this->itens[] = $item;
    $item->setPedido($this);
  }

  public function removerItem(ItemCardapio $item) {
    $index = array_search($item, $this->itens, true);
    if ($index !== false) {
      unset($this->itens[$index]);
      $item->setPedido(null);
    }
  }

  public function calcularPrecoTotal() {
    $total = 0;
    foreach ($this->itens as $item) {
      $total += $item->calcularPrecoFinal();
    }
    return $total;
  }

  public function getIdPedido() {
    return $this->idPedido;
  }
  public function getItens() {
    return $this->itens;
  }

  public function setIdPedido($idPedido) {
    $this->idPedido = $idPedido;
  }

  public function getPrecoTotal() {
    return $this->precoTotal;
  }

  public function setPrecoTotal($precoTotal) {
    $this->precoTotal = $precoTotal;
  }
}

class Pizza extends ItemCardapio {
  private $tamanho;
  private $ingredientes;

   public function __construct($id, $nome, $preco, $quantidade, $pedido, $tamanho, $ingredientes)
    {
        parent::__construct($id, $nome, $preco, $quantidade, $pedido);
        $this->tamanho = $tamanho;
        $this->ingredientes = $ingredientes;
    }

  public function calcularPrecoFinal() {
    $preco = parent::calcularPrecoFinal();
    if ($this->getTamanho() == 'grande') {
      $preco *= 1.5;
    }
    return $preco;
  }

  public function getTamanho() {
    return $this->tamanho;
  }

  public function setTamanho($tamanho) {
    $this->tamanho = $tamanho;
  }

  public function getIngredientes() {
    return $this->ingredientes;
  }

  public function setIngredientes($ingredientes) {
    $this->ingredientes = $ingredientes;
  }
}

class Massa extends ItemCardapio {
    private $tamanho;
    private $ingredientes;

    public function __construct($id, $nome, $preco, $quantidade, $pedido, $tamanho, $ingredientes)
    {
        parent::__construct($id, $nome, $preco, $quantidade, $pedido);
        $this->tamanho = $tamanho;
        $this->ingredientes = $ingredientes;
    }

    public function calcularPrecoFinal() {
        $preco = parent::getPreco();
        switch ($this->tamanho) {
            case 'pequeno':
                $preco *= 0.8;
                break;
            case 'grande':
                $preco *= 1.2;
                break;
            default:
                break;
        }
        return $preco;
    }

    public function getTamanho() {
        return $this->tamanho;
    }

    public function setTamanho($tamanho) {
        $this->tamanho = $tamanho;
    }

    public function getIngredientes() {
        return $this->ingredientes;
    }

    public function setIngredientes($ingredientes) {
        $this->ingredientes = $ingredientes;
    }
}

class Salada extends ItemCardapio
{
    private $ingredientesPrincipais;
    private $incluirProteina;

    public function __construct($id, $descricao, $preco, $pedido, $quantidade, $ingredientesPrincipais, $incluirProteina)
    {
        parent::__construct($id, $descricao, $preco,$pedido, $quantidade);
        $this->ingredientesPrincipais = $ingredientesPrincipais;
        $this->incluirProteina = $incluirProteina;
    }

    public function getIngredientesPrincipais()
    {
        return $this->ingredientesPrincipais;
    }

    public function setIngredientesPrincipais($ingredientesPrincipais)
    {
        $this->ingredientesPrincipais = $ingredientesPrincipais;
    }

    public function getIncluirProteina()
    {
        return $this->incluirProteina;
    }

    public function setIncluirProteina($incluirProteina)
    {
        $this->incluirProteina = $incluirProteina;
    }

    public function calcularPrecoFinal()
    {
        $precoBase = parent::calcularPrecoFinal();
        if ($this->incluirProteina) {
            $precoBase *= 1.1;
        }
        return $precoBase;
    }
}

$pedido = new Pedido();

$pizza = new Pizza(1, 'Pizza de Margherita', 30, 5, $pedido, 'grande', ['molho', 'queijo', 'manjericão']);
$pedido->adicionarItem($pizza);

$massa = new Massa(2, 'Espaguete à bolonhesa', 25, 10, $pedido, 'grande', ['espaguete', 'molho à bolonhesa']);
$pedido->adicionarItem($massa);

$salada = new Salada(3, 'Salada Caesar', 20, 8, $pedido, ['alface', 'croutons', 'queijo parmesão'], true);
$pedido->adicionarItem($salada);

echo "=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=<br>";
echo "Pedido Número : " . $pedido->getIdPedido() . "<br>";
echo "=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=<br>";

foreach ($pedido->getItens() as $item) {
    echo "Incluído Pedido item: " . $item->getId() . "<br>";
    echo "Descrição do Pedido: " . $item->getDescricao() . "<br>";
    echo "Valor do item: " . $item->getPreco() . "<br>";
    echo "Quantidade : " . $item->getQuantidade() . "<br>";
    
    if ($item instanceof Pizza) {
        echo "Tamanho : " . $item->getTamanho() . "<br>";
        echo "Ingredientes : " . implode(', ', $item->getIngredientes()) . "<br>";
    } elseif ($item instanceof Massa) {
        echo "Tamanho : " . $item->getTamanho() . "<br>";
        echo "Ingredientes : " . implode(', ', $item->getIngredientes()) . "<br>";
    } elseif ($item instanceof Salada) {
        echo "Ingredientes Principais: " . implode(', ', $item->getIngredientesPrincipais()) . "<br>";
        if ($item->getIncluirProteina()) {
            echo "Incluir proteína: sim<br>";
        } else {
            echo "Incluir proteína: não<br>";
        }
    }
    
    echo "-----------------//-----//-------------------<br>";
}

echo "-----------------------------------------------<br>";
echo "Preço total do pedido: $" . $pedido->calcularPrecoTotal() . "<br>";
echo "-----------------------------------------------<br>";
?>